<h1 style="text-align: center;">Mage Model</h1>

> Projeto com a finalidade de inserção de modelos via admin, setando para cada projeto pro cliente escolher e montar a sua imagem de acordo com os templates disponíveis.

## # <i>Tables</i>

>- [x] Model
- [x] id
- [x] name
- [x] image
- [x] project *fkey*

>- [x] Template
- [x] id
- [x] name
- [x] image
- [x] project *fkey*
- [x] user *fkey*

>- [x] User
- [x] id
- [x] name
- [x] project *fkey*
- [x] password
- [x] email

>- [x] Project
- [x] id
- [x] nome


## ## Fluxo

> /admin
- Autenticação na plataforma como admin.
- Inserção, listagem, remoção e edição de modelos para o projeto específico.
- Inserção, listagem, remoção e edição de usuários.
- Listagem de todos os templates realizdos.
- Inserção, listagem, remoção e edição de projetos.

> /login
- Autenticação na plataforma.

> /dashboard
- Listagem e remoção de templates criados.
- Criação de templates.

> /create
- Seleção de templates disponíveis

> /create/template
- Inserção
