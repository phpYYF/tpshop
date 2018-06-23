<?php
namespace app\admin\controller;
//主页类
class IndexController extends CommonController
{
    public function index()
    {
        return $this->fetch('');

    }
    public function top(){
    	return $this->fetch('');
    }
    public function left(){
    	return $this->fetch('');
    }
    public function main(){
    	return $this->fetch('');
    }
}
