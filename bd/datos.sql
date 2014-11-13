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
                                ('roberto', md5('roberto'), 1),
                                ('pepe', md5('pepe'), 1),
                                ('juan', md5('juan'), 1),
                                ('maria', md5('maria'), 1),
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


-- CLIENTES --

    insert into clientes (codigo, nombre, apellidos, dni, direccion, poblacion, codigo_postal, usuario_id)
                         values (110001, 'Roberto', 'López Gómez', '31224553H', 'Avda. de Compostela, 34',
                                 'Sanlúcar de Barrameda', '11540', 2),
                                (110002, 'José', 'Jiménez Mellado', '35667189J', 'General Prim, 26 1º Dcha.',
                                 'Sanlúcar de Barrameda', '11540', 3),
                                (410001, 'Juan', 'Rey Sanjosé', '48997345F', 'Avda. República Argentina, 45 Alto',
                                 'Sevilla', '41003', 4),
                                (410002, 'María José', 'Moreno Luque', '41823099P', 'Avda. de los Conquistadores, 44',
                                 'Dos Hermanas', '41234', 5);

-- PEDIDOS --

    insert into pedidos (numero, fecha, cliente_id, codigo, 
                         nombre, apellidos, dni, direccion, poblacion, codigo_postal)
                         values (14000001, current_date-4, 1, 110001, 'Roberto', 'López Gómez', '31224553H',
                                 'Avda. de Compostela, 34', 'Sanlúcar de Barrameda', '11540'),
                                (14000002, current_date-3, 1, 110001, 'Roberto', 'López Gómez', '31224553H',
                                 'Avda. de Compostela, 34', 'Sanlúcar de Barrameda', '11540'),
                                (14000003, current_date-2, 2, 410001, 'Juan', 'Rey Sanjosé', '48997345F', 
                                 'Avda. República Argentina, 45 Alto', 'Sevilla', '41003'),
                                (14000004, current_date-1, 2, 410002, 'María José', 'Moreno Luque', '41823099P',
                                 'Avda. de los Conquistadores, 44', 'Dos Hermanas', '41234');

-- LINEAS PEDIDOS --

    insert into lineas_pedidos (pedido_id, codigo, descripcion, precio, cantidad)
                       values (1, 8499786675192, 'Ibook Sandbook 7" e-ink WIFI-3G 16GB', 99.99, 1),
                              (1, 8433326765499, 'Monitor ACER 27" 2560x1980 pix. 3D', 255.00, 2), 
                              (1, 8433556543445, 'Disco duro Seagate Expansion 2 TB - 2,5", USB 3.0', 80.45, 2),
                              (2, 2887942650087, 'Portátil Lenovo 13,3" YOGA 2 Pro-13 i7 4500U', 480.45, 1), 
                              (2, 8433556543445, 'Disco duro Seagate Expansion 2 TB - 2,5", USB 3.0', 80.45, 2),
                              (3, 8499786675192, 'Ibook Sandbook 7" e-ink WIFI-3G 16GB', 99.99, 1),
                              (4, 8433556543445, 'Disco duro Seagate Expansion 2 TB - 2,5", USB 3.0', 80.45, 5);
