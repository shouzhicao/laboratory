<?php

require_once 'BaseController.php';
require_once APPLICATION_PATH.'/models/AdminInfo.php';
require_once APPLICATION_PATH.'/models/UserAuths.php';


class AdminController extends BaseController
{
    public function init(){
        BaseController::init();
    }
    
    //跳转到注册界面
    public function regAction(){
        $this->render('register');
    }
    
    //用户名验证函数
    public function verifAction(){

        //告诉浏览器返回的数据是xml格式
        header("Content-Type:text/xml;charset=utf-8");
        //告诉浏览器需要缓存数据
        header("Cache-Control:no-cache");
        $adminInfo = new AdminInfo();
        $adminname = $this->getRequest()->getParam('adminname');
        $where = "adminName='".$adminname."'";
        $result = $adminInfo->fetchRow($where);
        
        if(!empty($result)){
            exit("用户名已存在");
        }else{
            exit("用户名可用");
        }

    }
    //管理员注册处理
    public function regprocessAction(){
        $this->render('login');
    }
    
    //跳转到登录界面
    public function loginAction(){

        $this->render('login');
    }
    
    //管理员登录验证
    public function loginprocessAction(){      
        
        /*
        //先看验证码是否正确
        $checkCode = $_POST['checkcode'];
        session_start();
        if($checkCode != $_SESSION['myCheckCode']){
            //header("Location:loginUI.php?errno=2");
            $this->render('login');
            exit();
        }
        */
        //获取用户登录信֤
        $adminname = $this->getRequest()->getParam('adminname');
        $password = $this->getRequest()->getParam('password');
        

        $adminInfo = new AdminInfo();
        $where = "adminName='".$adminname."'";
        $result = $adminInfo->fetchRow($where);
        
        if(!empty($result)){
            if($password == $result['password']){  //登录成功

                $this->listAction(); //调用listAction()方法获取用户列表
                
            }else{  //登录失败（密码不正确）
                $this->view->errno = "密码不正确";
                $this->render('login');
            }
            
        }else{  //登录失败（用户名不存在）
            $this->view->errno = "用户名不存在";
            $this->render('login');
        }

    }
    /**
     * 根据用户id获取用户信息
     */
    public function getuserAction(){
        
        $id = $this->getRequest()->getParam('id');
        if($id != null){
            $user = new User();
            $db = $user->getAdapter();
            $where = $db->quoteInto("id=?", $id);
            $result = $user->fetchRow($where);
            
            if(!empty($result)){
                //获取成功
                $this->view->result = $result->toArray();
                $this->render('###');
            }else{
                //获取失败
                $arr = array("successed"=>false,"message"=>"");
                exit(json_encode($arr));
            }
        }else{
            //获取失败
            $arr = array("successed"=>false,"message"=>"");
            exit(json_encode($arr));
        }
        
    }
    
    /**
     * 获取用户信息
     */
    public function usereditAction(){
        
        $userId = $this->getRequest()->getParam('userId');
        
        $userAuths = new UserAuths();
        $where = "userId=".$userId;
        $result = $userAuths->fetchAll($where,1)->toArray();
        $this->view->userinfo = $result;
        $this->render('useredit');
    }
    
    /**
     * 将修改的信息存入数据库
     */
    public function usereditprocessAction(){
        
        $userId = $this->getRequest()->getParam('userId');
        $identityType = $this->getRequest()->getParam('identityType');
        $identifier = $this->getRequest()->getParam('identifier');
        $credential = $this->getRequest()->getParam('credential');
        $loginNum = $this->getRequest()->getParam('loginNum');
        $loginTime = $this->getRequest()->getParam('loginTime');
        $userAuths = new UserAuths();
        $where = "userId=".$userId;
        $data = array("userId"=>$userId,
                        "identityType"=>$identityType,
                        "identifier"=>$identifier,
                        "credential"=>$credential,
                        "loginNum"=>$loginNum,
                        "loginTime"=>$loginTime);
        $userAuths->update($data, $where);
        $this->listAction();
    }
    
    /**
     * 获取用户列表
     */
    public function listAction(){
        
        /*
        $startRowIndex = $this->getRequest()->getParam('startRowIndex');
        $pageSize = $this->getRequest()->getParam('pageSize');
        $orderBy = $this->getRequest()->getParam('orderBy');
        */
        $userAuths = new UserAuths();
        $result = $userAuths->fetchAll();
        $this->view->userlist = $result->toArray();
        $this->render('userlist');

        
    }
    
}

