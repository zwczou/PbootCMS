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

    public function del()
    {
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        if ($this->model->delReport($id)) {
            $this->log('删除友情链接' . $id . '成功！');
            success('删除成功！', - 1);
        } else {
            $this->log('删除友情链接' . $id . '失败！');
            error('删除失败！', - 1);
        }
    }

    public function mod()
    {

        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }

        // 修改操作
        if ($_POST) {
            // 获取数据
            $no = post('no');
            $name = post('name');
            $keyword = post('keyword');
            $path = post('path');
            
            if (! $no) {
                alert_back('编号不能为空！');
            }
            
            if (! $name) {
                alert_back('名称不能为空！');
            }
            
            if (! $path) {
                alert_back('链接不能为空！');
            }
            
            // 构建数据
            $data = array(
                'no' => $no,
                'name' => $name,
                'path' => $path,
                'keyword' => $keyword,
                'update_user' => session('username')
            );
            
            // 执行添加
            if ($this->model->modReport($id, $data)) {
                $this->log('修改友情链接' . $id . '成功！');
                if (! ! $backurl = get('backurl')) {
                    success('修改成功！', base64_decode($backurl));
                } else {
                    success('修改成功！', url('/admin/Report/index'));
                }
            } else {
                location(- 1);
            }
        } else {
            // 调取修改内容
            $this->assign('mod', true);
            if (! $result = $this->model->getReport($id)) {
                error('编辑的内容已经不存在！', - 1);
            }
            $this->assign('report', $result);
            $this->display('content/report.html');
        }
    }
}
