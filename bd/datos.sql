-- CARGA DATOS PARA PRUEBAS TIENDA ON LINE --
-- Partimos de la BBDD vacía --

-- ROLES --

    insert into roles (descripcion)
                      values ('Administrador'),
                             ('Registrado');

-- USUARIOS --

    insert into usuarios (nick, password, rol_id)
                         values ('admin', md5('admin'), 1),
                                ('roberto', md5('roberto'), 2),
                                ('pepe', md5('pepe'), 2),
                                ('juan', md5('juan'), 2),
                                ('maria', md5('maria'), 2);


-- ARTICULOS --

    insert into articulos (codigo, descripcion, precio, existencias)
        values  (2887942650087,'Portátil Lenovo 13,3" YOGA 2 Pro-13 i7 4500U',480.00,5),
                (4713147445028,'Portátil Acer 15,6" Aspire E5-571 i5 4210U',456.00,7),
                (3888182856291,'Portátil HP 17" Pavilion 17-e104ss i7 4702MQ',899.99,7),
                (8433556543445,'Disco duro Seagate Exp. 2 TB - 2,5", USB 3.0',80.00,7),
                (8433326765499,'Monitor ACER 27" 2560x1980 pix. 3D',255.00,7),
                (8456555874556,'Smartphone libre Sony Xperia T3 violeta',299.99,7),
                (8479855874523,'Impresora Láser Monocromo Brother HL-2135W WiFi',899.99,7),
                (8433558547856,'Impresora Multifunción Láser HP LJ Pro MFP M125a',110.00,7),
                (8438795715422,'Cámara réflex digital Nikon D7100 + DX 18-200 VR',1699.00,7),
                (8499786675192,'Ibook Sandbook 7" e-ink WIFI-3G 16GB',99.99,7),
                (4714562857555,'Smartwatch Sony SW2 Active Android Bluetooth',190.00,4),
                (3888182857575,'iPad mini 2 con pantalla Retina Wi-Fi 16 GB',269.00,7),
                (8433556822242,'Apple MacBook Air 13,3" MD760Y/B Intel Core i5',999.00,2),
                (8433478555452,'Proyector LG PB62G, 3D Ready, HDMI y USB DivXHD',499.00,6),
                (8458965856666,'eReader Sony PRS-T3 elink 6" táctil WiFi rojo',299.99,6),
                (8124587454663,'La Tierra Media: Sombras De Mordor Pc',43.00,5),
                (8789789894569,'Consola Ps4 De 500 Gb + Destiny',449.90,2),
                (7541254129855,'Altavoces Bose Companion 20',249.00,2),
                (8497854556231,'Disco duro WD My Passport Ultra 2 TB USB 3.0',99.99,2);


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
                                (14000003, current_date-2, 3, 410001, 'Juan', 'Rey Sanjosé', '48997345F', 
                                 'Avda. República Argentina, 45 Alto', 'Sevilla', '41003'),
                                (14000004, current_date-1, 4, 410002, 'María José', 'Moreno Luque', '41823099P',
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

