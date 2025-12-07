<?php

namespace WPFramework\Core;

/**
 * کلاس Auto-Updater از گیت‌هاب
 * 
 * این کلاس امکان آپدیت خودکار پلاگین از گیت‌هاب را فراهم می‌کند
 */
class GitHubUpdater
{
    /**
     * نام کاربری گیت‌هاب
     *
     * @var string
     */
    private $username;

    /**
     * نام repository
     *
     * @var string
     */
    private $repository;

    /**
     * Token گیت‌هاب (اختیاری - برای private repos)
     *
     * @var string|null
     */
    private $token;

    /**
     * Constructor
     *
     * @param string $username
     * @param string $repository
     * @param string|null $token
     */
    public function __construct(string $username, string $repository, ?string $token = null)
    {
        // Sanitize ورودی‌ها برای امنیت
        $this->username = \sanitize_user($username, true);
        $this->repository = \sanitize_file_name($repository);
        $this->token = $token ? \sanitize_text_field($token) : null;
    }

    /**
     * راه‌اندازی Auto-Updater
     *
     * @return void
     */
    public function init(): void
    {
        // فیلتر برای بررسی آپدیت
        \add_filter('pre_set_site_transient_update_plugins', [$this, 'checkForUpdate']);
        
        // فیلتر برای اطلاعات پلاگین
        \add_filter('plugins_api', [$this, 'pluginInformation'], 10, 3);
        
        // فیلتر برای دانلود از گیت‌هاب
        \add_filter('upgrader_pre_download', [$this, 'downloadFromGitHub'], 10, 3);
    }

    /**
     * بررسی وجود آپدیت جدید
     *
     * @param object $transient
     * @return object
     */
    public function checkForUpdate($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }

        $latestRelease = $this->getLatestRelease();

        if (!$latestRelease) {
            return $transient;
        }

        $currentVersion = WP_FRAMEWORK_VERSION;
        $latestVersion = $this->normalizeVersion($latestRelease->tag_name);

        if (version_compare($latestVersion, $currentVersion, '>')) {
            $pluginFile = \plugin_basename(WP_FRAMEWORK_PLUGIN_FILE);
            
            // استفاده از zipball_url - token در header ارسال می‌شود نه در URL
            $packageUrl = $latestRelease->zipball_url;
            
            $transient->response[$pluginFile] = (object) [
                'slug' => 'wp-framework',
                'plugin' => $pluginFile,
                'new_version' => \sanitize_text_field($latestVersion),
                'url' => \esc_url_raw($latestRelease->html_url),
                'package' => \esc_url_raw($packageUrl),
                'icons' => [],
                'banners' => [],
                'banners_rtl' => [],
                'tested' => \get_bloginfo('version'),
                'requires_php' => '7.4',
            ];
        }

        return $transient;
    }

    /**
     * دریافت اطلاعات پلاگین برای صفحه آپدیت
     *
     * @param false|object|array $result
     * @param string $action
     * @param object $args
     * @return false|object
     */
    public function pluginInformation($result, $action, $args)
    {
        if ($action !== 'plugin_information' || $args->slug !== 'wp-framework') {
            return $result;
        }

        $release = $this->getLatestRelease();

        if (!$release) {
            return $result;
        }

        // استفاده از zipball_url - token در header ارسال می‌شود
        $downloadUrl = $release->zipball_url;

        $info = (object) [
            'name' => 'WP Framework',
            'slug' => 'wp-framework',
            'version' => \sanitize_text_field($this->normalizeVersion($release->tag_name)),
            'author' => '<a href="' . \esc_url($release->author->html_url) . '">' . \esc_html($release->author->login) . '</a>',
            'homepage' => \esc_url_raw($release->html_url),
            'download_link' => \esc_url_raw($downloadUrl),
            'sections' => [
                'description' => $this->getDescription(),
                'changelog' => $this->formatChangelog($release->body),
            ],
            'banners' => [],
            'icons' => [],
            'requires' => '5.0',
            'tested' => \get_bloginfo('version'),
            'requires_php' => '7.4',
        ];

        return $info;
    }

    /**
     * دانلود از گیت‌هاب
     *
     * @param bool $reply
     * @param string $package
     * @param \WP_Upgrader $upgrader
     * @return bool|string|\WP_Error
     */
    public function downloadFromGitHub($reply, $package, $upgrader)
    {
        // بررسی اینکه URL از repository ماست
        if (strpos($package, 'github.com') === false) {
            return $reply;
        }

        // اگر token وجود دارد، باید در header ارسال شود نه در URL
        // WordPress خودش این کار را انجام می‌دهد
        return $reply;
    }

    /**
     * دریافت آخرین Release از گیت‌هاب
     *
     * @return object|null
     */
    private function getLatestRelease()
    {
        $cacheKey = 'wp_framework_latest_release';
        $cached = \get_transient($cacheKey);

        if ($cached !== false) {
            return $cached;
        }

        // استفاده از esc_url_raw برای امنیت URL
        $url = sprintf(
            'https://api.github.com/repos/%s/%s/releases/latest',
            \urlencode($this->username),
            \urlencode($this->repository)
        );

        $args = [
            'timeout' => 15,
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'WordPress',
            ],
        ];

        // استفاده از Authorization header برای token (امن‌تر از query string)
        if ($this->token) {
            $args['headers']['Authorization'] = 'token ' . \sanitize_text_field($this->token);
        }

        $response = \wp_remote_get($url, $args);

        if (\is_wp_error($response) || \wp_remote_retrieve_response_code($response) !== 200) {
            return null;
        }

        $body = \wp_remote_retrieve_body($response);
        $release = \json_decode($body, false);

        // بررسی صحت داده‌های دریافتی
        if (!$release || !\is_object($release) || !isset($release->tag_name) || !isset($release->zipball_url)) {
            return null;
        }

        // Cache برای 12 ساعت
        \set_transient($cacheKey, $release, 12 * \HOUR_IN_SECONDS);

        return $release;
    }

    /**
     * نرمال‌سازی نسخه (حذف v از ابتدا)
     *
     * @param string $version
     * @return string
     */
    private function normalizeVersion(string $version): string
    {
        return ltrim($version, 'v');
    }

    /**
     * دریافت توضیحات پلاگین
     *
     * @return string
     */
    private function getDescription(): string
    {
        // Escape تمام متغیرها برای جلوگیری از XSS
        $username = \esc_html($this->username);
        $repository = \esc_html($this->repository);
        $repoUrl = \esc_url("https://github.com/{$this->username}/{$this->repository}");
        
        return \sprintf(
            '<p>%s</p><p><strong>%s:</strong> <a href="%s">%s</a></p>',
            \esc_html__('یک فریمورک حرفه‌ای وردپرس با ساختار کلاس محور، Dependency Injection و Service Providers', 'wp-framework'),
            \esc_html__('Repository', 'wp-framework'),
            $repoUrl,
            \esc_html("https://github.com/{$username}/{$repository}")
        );
    }

    /**
     * فرمت کردن Changelog
     *
     * @param string $body
     * @return string
     */
    private function formatChangelog(string $body): string
    {
        if (empty($body)) {
            return '<p>' . \esc_html__('هیچ تغییری ثبت نشده است.', 'wp-framework') . '</p>';
        }

        // تبدیل Markdown به HTML ساده
        $body = \esc_html($body);
        $body = \nl2br($body);
        
        return '<div class="wp-framework-changelog">' . $body . '</div>';
    }
}

