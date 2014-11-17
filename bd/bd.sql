-- BD TIENDA ON LINE

drop table if exists roles cascade;

create table roles (
    id          bigserial   constraint pk_roles primary key,
    descripcion varchar(15)
);

drop table if exists usuarios cascade;

create table usuarios (
    id       bigserial   constraint pk_usuarios primary key,
    nick     varchar(15) not null constraint uq_usuarios_nick unique,
    password char(32)    not null constraint ck_password_valida
                         check (length(password) = 32),
    rol_id   bigint      not null constraint fk_usuarios_roles
                         references roles (id) on delete no action
                         on update cascade
);
 
drop table if exists clientes cascade;

create table clientes (
    id            bigserial    constraint pk_clientes primary key,
    codigo        numeric(6)   not null constraint uq_clientes_codigo unique,
    nombre        varchar(15)  not null,
    apellidos     varchar (30) not null,
    dni           varchar(9)   not null constraint uq_clientes_dni unique,
    direccion     varchar(40),
    poblacion     varchar(40),
    codigo_postal char(5)      constraint ck_clientes_codigo_postal
                               check (length(codigo_postal) = 5),
    usuario_id    bigint       not null constraint fk_clientes_usuarios
                               references usuarios (id)
                               on delete set null on update cascade
);

drop table if exists articulos cascade;

create table articulos (
    id          bigserial constraint pk_articulos primary key,
    codigo      numeric(13) not null constraint uq_articulos_codigo unique,
    descripcion varchar(50) not null,
    precio      numeric (6,2) not null,
    existencias int
);

drop table if exists pedidos cascade;

create table pedidos (
    id           bigserial    constraint pk_pedidos primary key,
    numero       numeric(8,0) not null constraint uq_pedidos_codigo unique,
    fecha        date         not null default CURRENT_DATE,
    cliente_id   bigint       constraint fk_pedidos_clientes
                              references clientes (id)
                              on delete set null on update cascade,
-- Se duplican los datos del cliente para tenerlos en esta misma tabla --
    codigo        numeric(6)   not null,
    nombre        varchar(15)  not null,
    apellidos     varchar (30) not null,
    dni           varchar(9)   not null,
    direccion     varchar(40),
    poblacion     varchar(40),
    codigo_postal char(5)      constraint ck_clientes_codigo_postal
                               check (length(codigo_postal) = 5),
    importe       numeric(8,2),
    gastos_envio  numeric(4,2)
);

drop table if exists lineas_pedidos cascade;

create table lineas_pedidos (
    id          bigserial    constraint pk_lineas_pedidos primary key,
    pedido_id   bigint       not null constraint fk_lineas_pedidos_pedidos
                             references pedidos (id)
                             on delete cascade on update cascade,
    cantidad    numeric(4,2) not null,

-- Se duplican los datos del artículo para tenerlos en esta misma tabla --
    codigo      numeric(13)  not null,
    descripcion varchar(50),
    precio      numeric(6,2) not null
);

