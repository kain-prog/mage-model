<?php

namespace App\Controller\Admin;

use App\Entity\Model;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
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
            ->setPageTitle('new', 'Criação de modelo')
            ->setPageTitle('edit', 'Editar modelo')
            ->setPaginatorPageSize(3)
            ->overrideTemplates([
                'crud/new' => 'admin/model/new.html.twig',
            ]);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW,
                fn (Action $action) => $action->setLabel('Novo Modelo')
            )
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER,
                fn (Action $action) => $action->setLabel('Criar e adicionar outro')
            )
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN,
                fn (Action $action) => $action->setLabel('Criar')
            );
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
                ->setBasePath('uploads/models')
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