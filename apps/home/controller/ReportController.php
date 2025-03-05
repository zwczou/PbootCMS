<?php
namespace app\home\controller;

use core\basic\Controller;
use app\home\model\ParserModel;
use core\basic\Url;

class ReportController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new ParserModel();
        $this->parser = new ParserController();
        $this->htmldir = $this->config('tpl_html_dir') ? $this->config('tpl_html_dir') . '/' : '';
    }

    // 留言新增
    public function get()
    {
        $no = request('no');
        if (!$no) {
            json(0, '报告编号不能为空');
            return;
        }
        $report = $this->model->getReport($no);
        if (!$report) {
            json(0, '报告不存在');
            return;
        }
        json(1, $report);
    }
}