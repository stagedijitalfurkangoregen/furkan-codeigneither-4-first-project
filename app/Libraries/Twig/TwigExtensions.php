<?php

namespace App\Libraries\Twig;
use App\Models\BrandsModel;
use App\Models\CategoryModel;
use App\Models\ContentModel;
//use App\Models\ContentTransModel;
use App\Models\FeaturesModel;
use App\Models\FixedContentModel;
use App\Models\LanguageModel;
use App\Models\SettingModel;
use App\Models\TranslationModel;
use CodeIgniter\Config\Services;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\URI;
use CodeIgniter\HTTP\UserAgent;
use Config\App;
use JsonException;
use ReflectionException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtensions extends AbstractExtension
{
    protected $request;
    protected $session;

    public $general=array(
        'base_url',
        'site_url'
    );

    public function __construct()
    {
        $config = new App();
        $this->request = Services::request($config);
        $this->session = Services::session($config);

    }

    public function getFunctions(): array
    {
        return array(
            'general' => new TwigFunction('general', [$this, 'functionGeneral'], ['is_safe' => ['html']]),
            'asset_path' => new TwigFunction('asset_path', [$this, 'asset_path']),
            'upload_path' => new TwigFunction('upload_path', [$this, 'upload_path']),
            'get_session' => new TwigFunction('get_session', [$this, 'get_session']),
            'file_exists' => new TwigFunction('file_exists', [$this, 'file_exists']),
            'file_get_contents' => new TwigFunction('file_get_contents', 'file_get_contents'),
            'is_upload_exists' => new TwigFunction('is_upload_exists', [$this,'is_upload_exists']),
            'getStatus' => new TwigFunction('getStatus', [$this,'getStatus']),
            'paymentStatus' => new TwigFunction('paymentStatus', [$this,'paymentStatus']),
            'uri_get_value' => new TwigFunction('uri_get_value', [$this,'uri_get_value']),
            'in_url_exists' => new TwigFunction('in_url_exists', [$this,'in_url_exists']),
            'get_file_size' => new TwigFunction('get_file_size', [$this,'get_file_size']),
            'get_file_name' => new TwigFunction('get_file_name', [$this,'get_file_name']),
            'get_file_ext' => new TwigFunction('get_file_ext', [$this,'get_file_ext']),
            'user_agent' => new TwigFunction('user_agent', [$this,'user_agent']),

            'get_setting' => new TwigFunction('get_setting', [$this,'get_setting']),
            'getFixedContent' => new TwigFunction('getFixedContent', [$this,'getFixedContent']),
            'get_category_detail' => new TwigFunction('get_category_detail', [$this,'get_category_detail']),
            'get_content' => new TwigFunction('get_content', [$this,'get_content']),
            'get_content_type' => new TwigFunction('get_content_type', [$this,'get_content_type']),
            'get_lang_value' => new TwigFunction('get_lang_value', [$this,'get_lang_value']),
            'get_feature_detail' => new TwigFunction('get_feature_detail', [$this,'get_feature_detail']),
            'get_count_content' => new TwigFunction('get_count_content', [$this,'get_count_content']),
            'getLangVal' => new TwigFunction('getLangVal', [$this,'getLangVal']),

            'get_vehicle_detail' => new TwigFunction('get_vehicle_detail', [$this,'get_vehicle_detail']),
            'get_brand_detail' => new TwigFunction('get_brand_detail', [$this,'get_brand_detail']),
        );
    }

    public function getFilters(): array
    {
        return array();
    }
    /**
     * @param string $func
     * @param array $data
     *
     * @return mixed
     */
    public function functionGeneral(string $func, ...$data)
    {
        if ($data) {
            return $this->general->$func(...$data);
        }

        return $this->general->$func();
    }


    /**
     * @param $filename
     * @return string
     */
    public function asset_path($filename): string
    {
        return base_url('/assets'.'/' . $filename).'/';
    }

    public function upload_path($filename): string
    {
        if($filename){
            return base_url('uploads').'/'.$filename;
//            return base_url('writable').'/'.$filename;
        }
        return base_url('uploads').DIRECTORY_SEPARATOR;
    }
    public function get_session($param)
    {
        if($param && $this->session->has($param)){
            return $this->session->get($param);
        }
        return null;
    }

    public function user_agent(): UserAgent
    {
        return $this->request->getUserAgent();
    }

    public function is_upload_exists($file): string
    {
        if($file && file_exists(WRITEPATH.DIRECTORY_SEPARATOR.$file)){
            return base_url().'/writable/'.$file;
        }
        return base_url().'/writable/'.$this->get_setting('default_image');
    }

    public function getStatus($param): ?string
    {
        $result=null;
        if($param!==null){
            switch ($param){
                case "2":
                case "0":
                    $result = '<span class="badge badge-light-warning fw-bolder px-4 py-1">Pasif</span>';
                    break;
                case "1":
                    $result = '<span class="badge badge-light-success fw-bolder px-4 py-1"><i class="mdi mdi-check"></i> Aktif</span>';
                    break;
                case "3":
                    $result = '<span class="badge badge-light-danger fw-bolder px-4 py-1">İptal Edilmiş</span>';
                    break;
                case "4":
                    $result = '<span class="badge badge-light-danger fw-bolder px-4 py-1">Silinmiş</span>';
                    break;
            }
        }else{
            $result = '<span class="badge badge-light-info fw-bolder px-4 py-3"> Parametre Yok</span>';
        }
        return $result;
    }
    public function paymentStatus($param): ?string
    {
        $result=null;
        if($param!==null){
            switch ($param){
                case "2":
                case "0":
                    $result = '<span class="badge bg-soft-warning text-warning">Ödeme Bekleniyor</span>';
                    break;
                case "1":
                    $result = '<span class="badge bg-soft-success text-success"><i class="mdi mdi-check"></i> Ödeme Başarılı</span>';
                    break;
                case "3":
                    $result = '<span class="badge bg-soft-danger text-info">İptal Edilmiş</span>';
                    break;
                case "4":
                    $result = '<span class="badge bg-soft-danger text-danger">Hata Oluştu</span>';
                    break;
            }
        }else{
            $result = '<span class="badge bg-soft-dark text-dark"> Parametre Yok</span>';
        }
        return $result;
    }

    public function uri_get_value($param){
        if($param){
            return $this->request->getGet($param);
        }
        return null;
    }
    public function in_url_exists($param): bool
    {
        $uri = new URI(current_url());
        $expCur = explode('/',$uri->getPath());
        if(in_array($param, $expCur, true)){
            return true;
        }

        return false;
    }
    public function get_file_size($filePath){
        $fp = WRITEPATH.$filePath;
        if(file_exists($fp)){
            $file = new File($fp);
            $size = $file->getSizeByUnit('kb');
            if($size > 1300){
                return $file->getSizeByUnit('mb').' Mb';
            }
            return $file->getSizeByUnit('kb').' Kb';
        }
        return 0;
    }
    public function get_file_ext($filePath){
        $fp = WRITEPATH.$filePath;
        if(file_exists($fp)){
            return (new File($fp))->guessExtension();
        }
        return 0;
    }
    public function get_file_name($filePath){
        $fp = WRITEPATH.$filePath;
        if(file_exists($fp)){
            $exp = explode('/',$filePath);
            return end($exp);
        }
        return 0;
    }


    public function get_setting($key=null, $lang='tr'){
        $model = new SettingModel();
        if($key){
            $getData = $model->where('setting_key',$key)->first();
            if($getData->with_lang and $getData->setting_value != null){
                $decodeJs = json_decode($getData->setting_value, true);
                return $decodeJs[$lang] ?:null;
            }
            return $getData->setting_value ?: null;
        }
        return null;
    }

    /**
     * @param $key
     * @param string $lang
     * @param null $title
     * @param null $value
     * @return null
     * @throws ReflectionException
     * @throws JsonException
     */
    public function getFixedContent($key, string $lang='tr', $title=null, $value=null, $inputType='text'){
        if($key){
            $fixedContent = new FixedContentModel();
            $langModel = new LanguageModel();
            $getFixedContent = $fixedContent->where('content_key',$key)->where('lang_key',$lang)->first();
            if($getFixedContent){
                return $getFixedContent->content_value;
            }
            $getLang = $langModel->findAll();
            $createData = array();
            $opts = array('inputType'=>$inputType);
            foreach ($getLang as $item){
                $getFixedContent = $fixedContent->where('content_key',$key)->where('lang_key',$item->lang_key)->first();
                if(!$getFixedContent){
                    $createData[]=array(
                        'content_key'=>$key,
                        'content_title'=>$title,
                        'content_value'=>$value?:$title,
                        'lang_key'=>$item->lang_key,
                        'options'=> json_encode($opts, JSON_THROW_ON_ERROR)
                    );
                }
            }
            $create = $fixedContent->insertBatch($createData);
            if($create){
                $getFixedContent = $fixedContent->where('content_key',$key)->where('lang_key',$lang)->first();
                return $getFixedContent->content_value;
            }
        }
        return null;
    }


    public function get_category_detail($param){
        $model = new CategoryModel();
        if($param){
            return $model->select('categories.*')->getWithLang()
                ->find($param);
        }
        return null;
    }

    public function get_content($key,$lang,$param,$defaultParam)
    {
        if($key){
            $model = new ContentModel();
            $transModel = new TranslationModel();
            $getTransData = $transModel->where('parent_id',$key)->where('type','contents')->first();
            return $getTransData->{$param} ?? $model->find($key)->{$defaultParam};
        }
        return null;
    }

    public function get_count_content($key,$param)
    {
        if($key){
            $model = new ContentModel();
            return $model->where('parent_id',$key)->where('type',$param)->countAllResults();
        }
        return null;
    }

    public function get_lang_value($key, $type='public', $col='parent_id')
    {
        $detail = array();
        $transModel = new TranslationModel();
        $getItems = $transModel->where($col,$key)->where('type',$type)->first();
        if($getItems){
            $detail = $getItems->value ? json_decode($getItems->value,true) :$getItems->value;
        }
        return $detail;
    }

    public function get_feature_detail($key)
    {
        if($key){
            $model = new FeaturesModel();
            return $model->find($key);
        }
        return null;
    }

    public function get_content_type($param): ?string
    {
        if($param){
            $output = null;
            switch ($param){
                case 'accordions':
                    $output = 'Akordiyonlar';
                    break;
                case 'tab_segment':
                    $output = 'Tab Sekmeler';
                    break;
                case 'photos':
                    $output = 'Fotoğraflar';
                    break;
                case 'documents':
                    $output = 'Dökümanlar';
                    break;
                case 'videos':
                    $output = 'Videolar';
                    break;
                case 'content':
                    $output = 'İçerikler';
                    break;
                default:
                    $output = 'İçerikler';
            }
            return $output;
        }
        return null;
    }

    public function getLangVal($val,$language)
    {
        if($val != null){
            $value = json_decode($val,true);
            if(is_array($value)){
                if(array_key_exists($language,$value)){
                    return $value[$language];
                }
            }
        }
        return null;
    }

    public function get_vehicle_detail($param)
    {
        $out = array();
        if($param){
            $model = new BrandsModel();
            $getModel = $model->find($param);
            return $getModel;
        }
        return null;
    }

    public function get_brand_detail($param)
    {
        $out = array();
        if($param){
            $model = new BrandsModel();
            $getModel = $model->find($param);
            return $getModel;
        }
        return null;
    }
}