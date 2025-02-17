<?php

namespace app\admin\controller\content;

use core\basic\Controller;
use app\admin\model\content\ReportModel;

class ReportController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new ReportModel();
    }

    // 报告列表
    public function index()
    {
        if ((!!$id = get('id', 'int')) && $result = $this->model->getReport($id)) {
            $this->assign('more', true);
            $this->assign('report', $result);
        } else {
            $this->assign('list', true);
            if (!!($field = get('field', 'var')) && !!($keyword = get('keyword', 'vars'))) {
                $result = $this->model->report($field, $keyword);
            } else {
                $result = $this->model->getList();
            }
            $this->assign('reports', $result);
        }
        $this->display('content/report.html');
    }

    public function add()
    {
        if (empty(($_POST))) {
            alert_back('请添加报告！');
            return;
        }

        $name = post('name');
        $path = post('path');
        $no = post('no');

        if (!$name) {
            alert_back('名称不能为空！');
            return;
        }

        if (!$path) {
            alert_back('链接不能为空！');
            return;
        }

        if (!no) {
            alert_back('编号不能为空！');
            return;
        }


        // 构建数据
        $data = array(
            // 'acode' => session('acode'),
            'no' => $no,
            'name' => $name,
            'keyword' => post('keyword'),
            'path' => $path,
            'create_user' => session('username'),
            'update_user' => session('username')
        );

        // 执行添加
        if ($this->model->addReport($data)) {
            $this->log('新增报告成功！');
            if (!!$backurl = get('backurl')) {
                success('新增成功！', base64_decode($backurl));
            } else {
                success('新增成功！', url('/admin/Report/index'));
            }
        } else {
            $this->log('新增报告失败！');
            error('新增失败！', -1);
        }
    }
}
