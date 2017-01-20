<?php
namespace Admin\Controller;

class HooksController extends BaseController
{
    public function index()
    {
        $num = 25;
        $p = I("get.page") ? I("get.page") : 1;
        cookie("prevUrl", "Admin/Hooks/index/page/" . $p);

        $hooks = D("Hooks")->getList(array(), false, "id desc", $p, $num);
        $this->assign('hooksList', $hooks);// 赋值数据集

        $count = D("Hooks")->getMethod(array(), "count");// 查询满足要求的总记录数
        $Page = new \Think\Page($count, $num);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig('theme', "<ul class='pagination no-margin pull-right'></li><li>%FIRST%</li><li>%UP_PAGE%</li><li>%LINK_PAGE%</li><li>%DOWN_PAGE%</li><li>%END%</li><li><a> %HEADER%  %NOW_PAGE%/%TOTAL_PAGE% 页</a></ul>");
        $show = $Page->show();// 分页显示输出

        $this->assign('page', $show);// 赋值分页输出
        $this->assign('url', "http://" . I("server.HTTP_HOST"));
        $this->display();
    }

    public function modHooks()
    {
        $Hooks = D("Hooks")->get(array("id" => I("get.id")), true);
        $this->assign("Hooks", $Hooks);
        $this->display("Hooks:addHooks");
    }

    public function delHooks()
    {
        D("Hooks")->del(array("id" => array("in", I("get.id"))));
        $this->success("删除成功", cookie("prevUrl"));
    }

    public function addHooks()
    {
        if (IS_POST) {
            $data = I("post.");
            if(I("post.id")){
                $data['update_time'] = date("Y-m-d H:i:s",time());
            }else{
                $data['create_time'] = date("Y-m-d H:i:s",time());
            }
            D("Hooks")->add($data);

            $this->success("保存成功", cookie("prevUrl"));
        } else {
            $this->display();
        }
    }
}