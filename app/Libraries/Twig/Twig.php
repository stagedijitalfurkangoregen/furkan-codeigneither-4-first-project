<?php
namespace App\Libraries\Twig;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Paths;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use Twig\Extra\Intl\IntlExtension;
class Twig
{

    /**
     * @var Environment
     */
    private $environment;

    /**
     * @var string
     */
    private $ext = '.twig';
    /**
     * @var bool Whether functions are added or not
     */
    private $functions_added = false;

    /**
     * @var FilesystemLoader
     */
    private $loader;

    private $functions_asis = [
        'base_url',
        'site_url',
        'password_hash',
        'password_verify',
        'json_decode',
        'file_get_contents',
        'file_exists',
        'session',
        'uri_string',
        'current_url',
        'previous_url',
        'url_is'
    ];
    /**
     * @var array Functions with `is_safe` option
     * @see https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
     */
    private $functions_safe = [
        'csrf_field',
        'csrf_meta',
        'csrf_header',
        'csrf_token',
        'csrf_hash',
        'session_id',
        'sanitize_filename',
        'form_open',
        'form_close',
        'form_error',
        'form_hidden',
        'form_input',
        'form_password',
        'form_textarea',
        'form_checkbox',
        'form_radio',
        'form_dropdown',
        'form_multiselect',
        'form_upload',
        'form_label',
        'form_fieldset',
        'form_fieldset_close',
        'form_open_multipart', 'form_submit',
        'form_button','form_reset',
        'set_value', 'set_radio', 'set_select', 'set_checkbox',
    ];
    public function __construct()
    {
        helper('form');
        helper('security');
        $appPaths = new Paths();
        $appViewPaths = $appPaths->viewDirectory;

        $loader = new FilesystemLoader($appViewPaths);
        $dataConfig=array();
        $dataConfig['cache'] = WRITEPATH . 'cache/twig';
        $dataConfig['auto_reload'] = true;
        $dataConfig['debug'] = ENVIRONMENT !== 'production';
        $dataConfig['cache'] = false;
        $dataConfig['autoescape'] = false;
        $this->environment = new Environment($loader, $dataConfig);
        $this->environment->addExtension(new TwigExtensions());
        $this->environment->addExtension(new DebugExtension());
        $this->environment->addExtension(new StringExtension());
        $this->environment->addExtension(new IntlExtension());
        $this->environment->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Istanbul');
    }

    public function render(string $file, array $array):string{
        try {
            $this->addFunctions();
            $template = $this->environment->load($file . $this->ext);
        } catch (LoaderError $error_Loader) {
            throw new PageNotFoundException($error_Loader);
        }
        return $template->render($array);
    }


    protected function addFunctions(): void
    {
        // Runs only once
        if ($this->functions_added) {
            return;
        }

        // as is functions
        foreach ($this->functions_asis as $function) {
            if (function_exists($function)) {
                $this->environment->addFunction(
                    new TwigFunction(
                        $function,
                        $function
                    )
                );
            }
        }

        // safe functions
        foreach ($this->functions_safe as $function) {
            if (function_exists($function)) {
                $this->environment->addFunction(
                    new TwigFunction(
                        $function,
                        $function,
                        ['is_safe' => ['html']]
                    )
                );
            }
        }

        // customized functions
        if (function_exists('anchor')) {
            $this->environment->addFunction(
                new TwigFunction(
                    'anchor',
                    [$this, 'safe_anchor'],
                    ['is_safe' => ['html']]
                )
            );
        }

        $this->environment->addFunction(
            new TwigFunction(
                'validation_list_errors',
                [$this, 'validation_list_errors'],
                ['is_safe' => ['html']]
            )
        );

        $this->functions_added = true;
    }

    /**
     * @param string $uri
     * @param string $title
     * @param array $attributes only array is acceptable
     * @return string
     */
    public function safe_anchor(
        string $uri = '',
        string $title = '',
        array $attributes = []
    ): string {
        $uri = esc($uri, 'url');
        $title = esc($title);

        $new_attr = [];
        foreach ($attributes as $key => $val) {
            $new_attr[esc($key)] = $val;
        }

        return anchor($uri, $title, $new_attr);
    }

    public function validation_list_errors(): string
    {
        return \Config\Services::validation()->listErrors();
    }

}