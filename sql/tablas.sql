create table users(
    id int auto_increment primary key,
    email varchar(100) unique,
    pass varchar(128),
    is_admin bool default 0
);
create table articulos(
    id int auto_increment primary key,
    nombre varchar(100) unique,
    descripcion text,
    precio decimal(6,2),
    stock int unsigned
);

insert into users(email, pass, is_admin) values('admin@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);
insert into users(email, pass, is_admin) values('user@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0);