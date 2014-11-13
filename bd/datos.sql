-- CARGA DATOS PARA PRUEBAS TIENDA ON LINE --
-- Partimos de la BBDD vacía --

-- ROLES --

    insert into roles (descripcion)
                      values ('Administrador'),
                             ('Registrado'),
                             ('Invitado');

-- USUARIOS --

    insert into usuarios (nick, password, rol_id)
                         values ('admin', md5('admin'), 1),
                                ('Pepe', md5('pepe'), 1),
                                ('Invitado', md5(''), 1);


-- ARTICULOS --

    insert into articulos (codigo, descripcion, precio, existencias)
                          values  (2887942650087,
                                   'Portátil Lenovo 13,3" YOGA 2 Pro-13 i7 4500U',
                                   480.45,
                                   3),
                                  (4713147445028,
                                   'Portátil Acer 15,6" Aspire E5-571 i5 4210U',
                                   456.98,
                                   1),
                                  (3888182856291,
                                   'Portátil HP 17" Pavilion 17-e104ss i7 4702MQ',
                                   899.99,
                                   1),
                                  (8433556543445,
                                   'Disco duro Seagate Expansion 2 TB - 2,5", USB 3.0',
                                   80.45,
                                   2),
                                  (8433326765499,
                                   'Monitor ACER 27" 2560x1980 pix. 3D',
                                   255.00,
                                   2),
                                  (8499786675192,
                                   'Ibook Sandbook 7" e-ink WIFI-3G 16GB',
                                   99.99,
                                   2);