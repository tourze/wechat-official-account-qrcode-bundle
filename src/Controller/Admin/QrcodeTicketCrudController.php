<?php

declare(strict_types=1);

namespace WechatOfficialAccountQrcodeBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;

#[AdminCrud(routePath: '/wechat-official-account-qrcode/qrcode-ticket', routeName: 'wechat_official_account_qrcode_qrcode_ticket')]
final class QrcodeTicketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return QrcodeTicket::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('二维码Ticket')
            ->setEntityLabelInPlural('二维码Ticket')
            ->setSearchFields(['id', 'ticket', 'sceneId', 'sceneStr'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(30)
            ->showEntityActionsInlined()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->onlyOnIndex()
        ;

        yield BooleanField::new('valid', '有效状态')
            ->setHelp('标识二维码Ticket是否有效')
        ;

        yield AssociationField::new('account', '公众号账户')
            ->setFormTypeOption('choice_label', 'name')
            ->setHelp('关联的微信公众号账户')
        ;

        yield DateTimeField::new('expireTime', '过期时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->setRequired(false)
            ->setHelp('二维码的过期时间（可选，为空表示永不过期）')
        ;

        yield ChoiceField::new('actionName', '二维码类型')
            ->setChoices([
                '临时的整型参数值' => QrcodeActionName::QR_SCENE,
                '临时的字符串参数值' => QrcodeActionName::QR_STR_SCENE,
                '永久的整型参数值' => QrcodeActionName::QR_LIMIT_SCENE,
                '永久的字符串参数值' => QrcodeActionName::QR_LIMIT_STR_SCENE,
            ])
            ->setRequired(true)
            ->setHelp('二维码的动作类型')
        ;

        yield IntegerField::new('sceneId', '场景ID')
            ->setHelp('场景值ID，临时二维码时为32位非0整型')
            ->hideOnIndex()
        ;

        yield TextField::new('sceneStr', '场景字符串')
            ->setHelp('场景值字符串，永久二维码时字符串类型')
            ->setFormTypeOption('attr', ['maxlength' => 64])
            ->hideOnIndex()
        ;

        yield TextField::new('ticket', 'Ticket')
            ->setHelp('获取的二维码ticket，凭借此ticket可以在有效时间内换取二维码')
            ->setFormTypeOption('attr', ['maxlength' => 128])
            ->hideOnIndex()
        ;

        yield UrlField::new('url', '二维码URL')
            ->setHelp('二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片')
            ->setFormTypeOption('attr', ['maxlength' => 255])
            ->hideOnIndex()
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('valid', '有效状态'))
            ->add(EntityFilter::new('account', '公众号账户'))
            ->add(ChoiceFilter::new('actionName', '二维码类型')->setChoices([
                '临时的整型参数值' => QrcodeActionName::QR_SCENE->value,
                '临时的字符串参数值' => QrcodeActionName::QR_STR_SCENE->value,
                '永久的整型参数值' => QrcodeActionName::QR_LIMIT_SCENE->value,
                '永久的字符串参数值' => QrcodeActionName::QR_LIMIT_STR_SCENE->value,
            ]))
            ->add(NumericFilter::new('sceneId', '场景ID'))
            ->add(TextFilter::new('sceneStr', '场景字符串'))
            ->add(TextFilter::new('ticket', 'Ticket'))
            ->add(DateTimeFilter::new('expireTime', '过期时间'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
            ->add(DateTimeFilter::new('updateTime', '更新时间'))
        ;
    }
}
