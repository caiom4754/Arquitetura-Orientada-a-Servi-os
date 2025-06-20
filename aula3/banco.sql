create database Soa2025;
use Soa2025;

create table Produto(
	id int not null	auto_increment primary key,
    descricao varchar(100) not null,
    valor decimal(15,2) not null,
    estatus varchar(10) not null
);

select *from Produto;

insert into Produto
(descricao, valor, estatus)
values
('produto 3', 100.00, 'INATIVO');

insert into Produto
(descricao, valor, estatus)
values
('produto 2', 100.00, 'INATIVO');

insert into Produto
(descricao, valor, estatus)
values
('produto 1', 100.00, 'INATIVO');

insert into Produto
(descricao, valor, estatus)
values
('produto 4', 90.00, 'ATIVO');

insert into Produto
(descricao, valor, estatus)
values
('produto 5', 80.00, 'ATIVO');

insert into Produto
(descricao, valor, estatus)
values
('produto 6', 70.00, 'ATIVO');

alter table Produto
add column registro smallint not null default '1';

select * from Produto;

create table GRUPO(
id_ int not null primary key,
descricao_ varchar(150) not null,
estatus_ varchar(15)
);

alter table GRUPO
modify column id_ int auto_increment not null;

alter table Produto
add column grupo_id int not null;

select * from Produto;
select * from GRUPO;