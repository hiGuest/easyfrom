<?php

/**
 * 应用字段类型模型
 *
 * @author Will - Wang Yan <82901106@qq.com>
 * @copyright (c) 2017, wpgcms.com
 *
 */

namespace Guest\EasyFrom;

class FieldType {

    public function name() {
        return $this->fieldName();
    }

    public function content() {
        return $this->fieldVarchar();
    }

    public function text() {
        return $this->fieldText();
    }

    public function number() {
        return $this->fieldInt();
    }

    public function mobile() {
        return $this->fieldVarchar(11);
    }

    public function phone() {
        return $this->fieldVarchar(20);
    }

    public function email() {
        return $this->fieldVarchar(60);
    }

    public function textarea() {
        return $this->fieldText();
    }

    public function editor() {
        return $this->fieldText();
    }

    public function date() {
        return $this->fieldDate();
    }

    public function time() {
        return $this->fieldTime();
    }

    public function datetime() {
        return $this->fieldDatetime();
    }

    public function select() {
        return $this->fieldVarchar(50);
    }

    public function radio() {
        return $this->fieldVarchar(50);
    }

    public function checkbox() {
        return $this->fieldText();
    }

    public function image() {
        return $this->fieldVarchar();
    }

    public function images() {
        return $this->fieldText();
    }

    public function file() {
        return $this->fieldVarchar();
    }

    public function files() {
        return $this->fieldText();
    }

    public function video() {
        return $this->fieldVarchar();
    }

    public function price() {
        return $this->fieldDecimal();
    }

    public function color() {
        return $this->fieldVarchar();
    }

    public function area() {
        return $this->fieldVarchar();
    }

    public function amap() {
        return $this->fieldText();
    }

    public function category() {
        return $this->fieldInt();
    }

    public function wpg_goupcontrols(){
        return $this->fieldText();
    }

    protected function fieldVarchar($len = 250) {
        return [
            'type' => 'varchar',
            'len' => $len,
            'decimal' => 0,
            'default' => ''
        ];
    }

    protected function fieldName($len = 60) {
        return [
            'type' => 'varchar',
            'len' => $len,
            'decimal' => 0,
            'default' => ''
        ];
    }

    protected function fieldInt($len = 11) {
        return [
            'type' => 'int',
            'len' => $len,
            'decimal' => 0,
            'default' => 0
        ];
    }

    protected function fieldText() {
        return [
            'type' => 'text',
            'len' => 0,
            'decimal' => 0,
            'default' => ''
        ];
    }

    protected function fieldFloat($len = 11, $decimal = 2) {
        return [
            'type' => 'float',
            'len' => $len,
            'decimal' => $decimal,
            'default' => 0
        ];
    }

    protected function fieldDecimal($len = 11, $decimal = 2) {
        return [
            'type' => 'decimal',
            'len' => $len,
            'decimal' => $decimal,
            'default' => 0
        ];
    }

    protected function fieldDate() {
        return [
            'type' => 'date',
            'len' => 0,
            'decimal' => 0
        ];
    }

    protected function fieldTime() {
        return [
            'type' => 'time',
            'len' => 0,
            'decimal' => 0
        ];
    }

    protected function fieldDatetime() {
        return [
            'type' => 'datetime',
            'len' => 0,
            'decimal' => 0
        ];
    }

    static public function type() {
        $list = [
            'text' => '文本框',
            'mobile' => '手机',
            'phone' => '电话',
            'email' => '邮箱',
            'number' => '数字',
            'price' => '价格',
            'textarea' => '多行文本框',
            'editor' => '编辑器',
            'date' => '日期',
            'time' => '时间',
            'datetime' => '日期时间',
            'select' => '下拉菜单',
            'radio' => '单选框',
            'checkbox' => '复选框',
            'image' => '图片上传',
            'images' => '多图上传',
            'file' => '文件上传',
            'files' => '多文件上传',
            'video' => '视频上传',
            'color' => '颜色选择',
            'area' => '省市区选择',
            'amap' => '高德地图',
            'category' => '分类系统',
            'wpg_goupcontrols' => 'WPG分组控件'
        ];

        return $list;
    }

}
