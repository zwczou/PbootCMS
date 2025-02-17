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
    public function index()
    {
        $reportInfo = '';
        if ($_POST) {
            $no = post('no');
            if (! $no) {
                alert_back('编号不能为空！');
            }
            $report = $this->model->getReport($no);
            if (! $report) {
                alert_back('报告不存在！');
            }
            $reportInfo = sprintf('<img src="%s" alt="%s" />', $report->path, $report->name);
        }

        $content = parent::parser($this->htmldir . 'report.html'); // 框架标签解析
        $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
        $content = str_replace('{pboot:pagetitle}', '报告查询-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
        $content = str_replace('{pboot:report}', $reportInfo, $content);
        $content = $this->parser->parserPositionLabel($content, 0, '报告查询', Url::home('report')); // CMS当前位置标签解析
        $content = $this->parser->parserSpecialPageSortLabel($content, - 3, '报告查询', Url::home('report')); // 解析分类标签
        $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
        echo $content;
        exit();
    }
}