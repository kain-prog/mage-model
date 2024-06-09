<?php

namespace App\Controller\Admin;

use App\Entity\Model;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;

class ModelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Model::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Modelos')
            ->setEntityLabelInSingular('Modelo')
            ->setPageTitle('index', 'Gerenciamento de modelos')
            ->setPageTitle('new', 'Criação de Modelo')
            ->setPaginatorPageSize(3)
            ->overrideTemplates([
                'crud/new' => 'admin/model/new.html.twig',
            ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')
                ->setLabel('Nome')
                ->setFormTypeOption('attr', ['placeholder' => 'Insira um nome para o modelo']),
            IdField::new('id')->hideOnForm(),
            TextField::new('image'),
            TextField::new('fonts'),
            ImageField::new('thumb')
                ->setUploadDir('public/uploads/models'),
            AssociationField::new('company')
                ->setLabel('Empresa')
                ->setFormTypeOption('class', Company::class)
                ->setFormTypeOption('query_builder', function (CompanyRepository $companyRepository) {
                    return $companyRepository->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                })
                ->setFormTypeOption('placeholder', 'Selecione uma Empresa')
                ->setRequired(true),
        ];
    }
}