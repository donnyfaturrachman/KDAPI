<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\ContentModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
class ContentController extends Controller{
    public $success_status = 200;
    public function index(Request $request){
        $input = $request->all();
        $content=ContentModel::select('*')->orderBy('date_publish', 'desc');
        if($request->get('title')){
            $content->where("title", "LIKE","%{$request->get('title')}%");
        }
        if($request->get('summary')){
            $content->where("summary", "LIKE","%{$request->get('summary')}%");
        }
        if($request->get('body')){
            $content->where("body", "LIKE","%{$request->get('body')}%");
        }
        if($request->get('id_category')){
            $content->where("id_category", "=","{$request->get('id_category')}");
        }
        if($request->get('id_topics')){
            $content->where("id_topics", "=","{$request->get('id_topics')}");
        }
        if($request->get('id_editor')){
            $content->where("id_editor", "=","{$request->get('id_editor')}");
        }
        if($request->get('id_penulis')){
            $content->where("id_penulis", "=","{$request->get('id_penulis')}");
        }
        if($request->get('id_reporter')){
            $content->where("id_reporter", "=","{$request->get('id_reporter')}");
        }
        if($request->get('arr_exclude')){
            $arr_exclude = json_decode($request->get('arr_exclude'));
            $str_exclude = implode(",", $arr_exclude);
            $content->where("id", "NOT IN", ($str_exclude) );
        }   
        if($request->get('id_subtitle')){
            $content->where("id_subtitle", "=","{$request->get('id_subtitle')}");
        }
        if($request->get('id_translator')){
            $content->where("id_translator", "=","{$request->get('id_translator')}");
        }
        if($request->get('slug')){
            $content->where("slug", "=","{$request->get('slug')}");
        }
        if($request->get('tipe')){
            $content->where("tipe", "=","{$request->get('tipe')}");
        }
        if($request->get('status')){
            $content->where("status", "=","{$request->get('status')}");
        }
        if($request->get('language')){
            $content->where("language", "=","{$request->get('language')}");
        }
        if($request->get('headline')){
            $content->where("headline", "=","{$request->get('headline')}");
        }
        if($request->get('is_breaking_news')){
            $content->where("is_breaking_news", "=","{$request->get('is_breaking_news')}");
        }
        if($request->get('position')){
            $content->where("position", "=","{$request->get('position')}");
        }
        if($request->get('tipe')){
            $content->where("tipe", "=","{$request->get('tipe')}");
        }
        if($request->get('is_old')){
            $content->where("is_old", "=","{$request->get('is_old')}");
        }
        if($request->get('tipe_foto')){
            $content->where("tipe_foto", "=","{$request->get('tipe_foto')}");
        }
        if($request->get('is_adv')){
            $content->where("is_adv", "=","{$request->get('is_adv')}");
        }
        if($request->get('is_adv')){
            $content->where("is_adv", "=","{$request->get('is_adv')}");
        }
        if($request->get('adv_zone')){
            $content->whereRaw('FIND_IN_SET(?,adv_zone)', $request->get('adv_zone'));
        }
        if($request->get('adv_date_start')){
            $content->where("adv_date_start", ">=","{$request->get('adv_date_start')}");
        }
        if($request->get('adv_date_end')){
            $content->where("adv_date_end", "<=","{$request->get('adv_date_end')}");
        }
        if($request->get('date_publish')){
            $content->where("date_publish", ">=","{$request->get('date_publish')}");
        }
        if($request->get('is_microsite')){
            $content->where("is_microsite", "=","{$request->get('is_microsite')}");
        }
        if($request->get('position_breaking_news')){
            $content->where("position_breaking_news", "=","{$request->get('position_breaking_news')}");
        }
        if($request->get('id_naskah')){
            $content->where("id_naskah", "=","{$request->get('id_naskah')}");
        }
        if($request->get('post_socmed')){
            $content->where("post_socmed", "=","{$request->get('post_socmed')}");
        }
        $content = $content->paginate(8);
        $content = $this->__getCategory($content);
        $content = $this->__getTopics($content);
        $content = $this->__getFoto($content);
        $content = $this->__getImages($content);
         $content = $this->__getImageCover($content);
        return response()->json(['data'=>$content],$this->success_status);
    }

    private function __getImageCover($content)
    {
        if(isset($content) && count($content)>0){
            foreach ($content as $key => $value) {
                if($value->id_image_cover != ''){
                    $tempdata = '';
                    $tempdata =  DB::select("
                    SELECT 
                    id
                    ,title
                    ,path 
                    ,description
                    ,src
                    ,is_old
                    ,tipe
                    ,path_video
                    FROM images WHERE id  = '".$value->id_image_cover."' 
                 ");
                 if(isset($tempdata[0]) && $tempdata[0] != ''){
                     $content[$key]['imagescover'] = $tempdata[0];
                 }
                     
                }else{
                    $content[$key]['imagescover'] = 0;
                }
            }
     }
     return $content;
    }
    private function __getImages($content)
    {
        if(isset($content) && count($content)>0){
            foreach ($content as $key => $value) {
                if($value->id_image != ''){
                    $tempdata = '';
                    $tempdata =  DB::select("
                    SELECT 
                    id
                    ,title
                    ,path 
                    ,description
                    ,src
                    ,is_old
                    ,tipe
                    ,path_video
                    FROM images WHERE id  = '".$value->id_image."' 
                 ");
                 if(isset($tempdata[0]) && $tempdata[0] != ''){
                     $content[$key]['images'] = $tempdata[0];
                 }
                     
                }else{
                    $content[$key]['images'] = 0;
                }
            }
     }
     return $content;
    }
    private function __getCategory($content){
        if(isset($content) && count($content)>0){
           	foreach ($content as $key => $value) {
           		if($value->id_category != ''){
           			$tempdata = '';
           			$tempdata =  DB::select("
                       SELECT 
                       id,
                       name,
                       slug,
                       date_created,
                       date_modified,
                       creator,
                       modifier,
                       language,
                       tipe,
                       is_top_menu,
                       is_microsite,
                       id_advertiser
                       FROM category WHERE id  = '".$value->id_category."' 
            		");
            		if(isset($tempdata[0]->name) && $tempdata[0]->name != ''){
            			$content[$key]['category'] = $tempdata[0]->name;
            		}
           		 	
           		}else{
           			$content[$key]['category'] = 0;
           		}
           	}
        }
        return $content;
    }
    private function __getTopics($content){
        if(isset($content) && count($content)>0){
           	foreach ($content as $key => $value) {
           		if($value->id_topics != ''){
           			$tempdata = '';
           			$tempdata =  DB::select("
                       SELECT 
                       id,
                       name,
                       slug,
                       date_created,
                       date_modified,
                       creator,
                       modifier,
                       language
                       FROM topics WHERE id  = '".$value->id_topics."' 
            		");
            		if(isset($tempdata[0]->name) && $tempdata[0]->name != ''){
            			$content[$key]['topics'] = $tempdata[0]->name;
            		}
           		 	
           		}else{
           			$content[$key]['topics'] = 0;
           		}
           	}
        }
        return $content;
    }
    private function __getDetailFoto($content){
        if(isset($content) && count($content)>0){
            foreach ($content as $key => $value) {
                if($value->id_image != 0){
                    $tempdata = '';
           			$tempdata =  DB::select("
                       SELECT 
                       id
                       ,title
                       ,path 
                       ,description
                       ,src
                       ,is_old
                       ,tipe
                       ,path_video
                       FROM images WHERE id  = '".$value->id_image."' 
            		");
            		if(isset($tempdata) && count($tempdata)>0){
                        return $tempdata;
            		}
                }
            }
        }
    }
    private function __getFoto($content){
        if(isset($content) && count($content)>0){
           	foreach ($content as $key => $value) {
           		if($value->id != ''){
           			$tempdata = '';
           			$tempdata =  DB::select("
                       SELECT 
                       id,
                       id_content,
                       id_images,
                       caption,
                       position
                      FROM rel_content_images WHERE id_content  = '".$value->id."' 
            		");
            		if(isset($tempdata) && count($tempdata)>0){
                        $content[$key]['foto'] = $this->__getDetailFoto($tempdata);
            		}
           		 	
           		}else{
           			$content[$key]['foto'] = 0;
           		}
           	}
        }
        return $content;
    }
}
    