<?php

/**
 * 功能：文件上传下载接口类
 */
require_once 'BaseController.php';
require_once APPLICATION_PATH.'/controllers/commontool/File.php';
class FileController extends BaseController{

    public function init(){
        BaseController::init();
    }
    
    /**
     * 文件上传接口
     */
    public function uploadAction(){
        
        //$this->render('upload');
        $this->uploadprocessAction();
        
    }
    
    
    public function uploadprocessAction(){

         //获取文件信息
         $fileInfo = $_FILES["uploadedfile"];
         $arr = null;
         $file = new File();
         $result = $file->fileUpload($fileInfo);
         
         // result=1 上传成功
         // result=2 文件已存在
         // result=0 上传失败
         if($result == 1){
             
            $arr = array("successed"=>true,"message"=>"upload success");
            
         }
         else if($result == 2){
             
            $arr = array("successed"=>false,"message"=>"file exist");

         }
         else if($result == 0){
             
            $arr = array("successed"=>false,"message"=>"upload failed");
            
         }
         exit(json_encode($arr));
    }
    /**
     * 课程文件下载接口
     */
    public function downloadAction(){
        
        $url = $this->getRequest()->getParam("url");
        
        $arr = array("successed"=>false,"message"=>"登录失败");
        exit(json_encode($arr));
    }
    
    /**
     * 音频/视频下载接口
     */
    public function downaudioAction(){
        $this->render('upload');
    }
    
}