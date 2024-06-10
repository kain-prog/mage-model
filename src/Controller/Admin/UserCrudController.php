<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\User;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public static function getEntityFqcn(): string
    {
            return User::class;

    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW,
                fn (Action $action) => $action->setLabel('Novo Usuário')
            )
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER,
                fn (Action $action) => $action->setLabel('Criar e adicionar outro')
            )
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN,
                fn (Action $action) => $action->setLabel('Criar')
            );
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Usuários')
            ->setEntityLabelInSingular('Usuário')
            ->setPageTitle('index', 'Gerenciamento de usuários')
            ->setPageTitle('new', 'Criação de usuários')
            ->setPageTitle('edit', 'Editar usuário')
            ->setPaginatorPageSize(5);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username')
                ->setLabel('Usuário')
                ->setFormTypeOption('attr', ['placeholder' => 'Insira um nome de usuário' ]),
            TextField::new('email')
                ->setFormTypeOption('attr', ['placeholder' => 'Insira um e-mail válido' ]),
            TextField::new('password', 'Senha')
                ->setFormTypeOption('attr', ['placeholder' => 'Insira um uma senha' ])
                ->setFormType(PasswordType::class)
                ->onlyOnForms()
                ->setRequired(true),
            AssociationField::new('company')
                ->setLabel('Empresa')
                ->setFormTypeOption('class', Company::class)
                ->setFormTypeOption('query_builder', function (CompanyRepository $companyRepository) {
                    return $companyRepository->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                })
                ->setFormTypeOption('placeholder', 'Selecione uma Empresa', )
                ->setRequired(true),
            ChoiceField::new('roles')
                ->setLabel('Permissões')
                ->setChoices([
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ])
                ->allowMultipleChoices()
                ->renderExpanded()
                ->setRequired(true),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $this->encodePassword($entityInstance);
            $this->ensureRoles($entityInstance);
            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $this->encodePassword($entityInstance);
            $this->ensureRoles($entityInstance);
            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }

    private function encodePassword(User $user): void
    {
        if ($user->getPassword() !== null) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
        }
    }

    private function ensureRoles(User $user):void
    {
        if (empty($user->getRoles()) || $user->getRoles() === ['ROLE_USER']) {
            $user->setRoles(['ROLE_USER']);
        }
    }
}
