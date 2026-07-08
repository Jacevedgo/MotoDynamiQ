-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-07-2026 a las 02:08:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `motodynamiq`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`) VALUES
(1, 'Urbana'),
(2, 'Deportiva'),
(3, 'Scooter'),
(4, 'Doble Proposito'),
(5, 'Crucero'),
(6, 'Enduro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `telefono`, `email`, `direccion`) VALUES
(1, 'Ana Gomez', '3001234567', 'ana.gomez@email.com', 'Calle 10 # 20-30'),
(2, 'Andres Cardenas', '3109876543', 'andres.cardenas@email.com', 'Carrera 5 # 10-15'),
(3, 'Laura Lopez', '3156789012', 'laura.lopez@email.com', 'Avenida Siempre Viva 123'),
(4, 'Carlos Ruiz', '3201122334', 'carlos.ruiz@email.com', 'Calle Falsa 456'),
(5, 'Maria Torres', '3114455667', 'maria.torres@email.com', 'Transversal 8 # 9-10'),
(6, 'Juan Perez', '3056677889', 'juan.perez@email.com', 'Calle 1 # 2-3'),
(7, 'Elena Martinez', '3123344556', 'elena.martinez@email.com', 'Carrera 10 # 20-30'),
(8, 'Diego Ramirez', '3189900112', 'diego.ramirez@email.com', 'Calle 50 # 10-20'),
(9, 'Sofia Herrera', '3012233445', 'sofia.herrera@email.com', 'Carrera 30 # 40-50'),
(10, 'Javier Ortiz', '3165566778', 'javier.ortiz@email.com', 'Calle 100 # 5-10'),
(11, 'Valentina Castro', '3190011223', 'valentina.castro@email.com', 'Carrera 7 # 12-14'),
(12, 'Felipe Morales', '3207788990', 'felipe.morales@email.com', 'Calle 20 # 30-40'),
(13, 'Camila Vargas', '3141122334', 'camila.vargas@email.com', 'Carrera 15 # 5-6'),
(14, 'Santiago Silva', '3174455667', 'santiago.silva@email.com', 'Calle 80 # 90-100'),
(15, 'Isabella Rojas', '3023344556', 'isabella.rojas@email.com', 'Carrera 20 # 10-20'),
(16, 'Mateo Mendoza', '3139900112', 'mateo.mendoza@email.com', 'Calle 9 # 8-7'),
(17, 'Lucia Paredes', '3055566778', 'lucia.paredes@email.com', 'Carrera 40 # 50-60'),
(18, 'Sebastian Gil', '3101122334', 'sebastian.gil@email.com', 'Calle 2 # 4-6'),
(19, 'Mariana Rios', '3124455667', 'mariana.rios@email.com', 'Carrera 60 # 70-80'),
(20, 'Nicolas Vega', '3187788990', 'nicolas.vega@email.com', 'Calle 30 # 20-10'),
(21, 'Gabriela Muñoz', '3014455667', 'gabriela.munoz@email.com', 'Carrera 5 # 4-3'),
(22, 'Alejandro Rios', '3169900112', 'alejandro.rios@email.com', 'Calle 70 # 60-50'),
(23, 'Daniela Paz', '3191122334', 'daniela.paz@email.com', 'Carrera 90 # 80-70'),
(24, 'Tomas Duque', '3205566778', 'tomas.duque@email.com', 'Calle 6 # 5-4'),
(25, 'Clara Leon', '3147788990', 'clara.leon@email.com', 'Carrera 3 # 2-1'),
(26, 'Pablo Soles', '3171122334', 'pablo.solis@email.com', 'Calle 40 # 30-20'),
(27, 'Natalia Cruz', '3024455667', 'natalia.cruz@email.com', 'Carrera 10 # 5-2'),
(28, 'Hugo Diaz', '3137788990', 'hugo.diaz@email.com', 'Calle 20 # 15-10'),
(29, 'Sara Luna', '3051122334', 'sara.luna@email.com', 'Carrera 70 # 80-90'),
(30, 'Oscar Rivas', '3104455667', 'oscar.rivas@email.com', 'Calle 90 # 100-110');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `proveedor_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `fecha`, `proveedor_id`, `usuario_id`, `total`) VALUES
(1, '2026-06-01', 1, 1, 1500000.00),
(2, '2026-06-01', 2, 1, 2000000.00),
(3, '2026-06-02', 3, 2, 500000.00),
(4, '2026-06-02', 4, 1, 1200000.00),
(5, '2026-06-03', 5, 2, 800000.00),
(6, '2026-06-03', 6, 1, 300000.00),
(7, '2026-06-04', 7, 2, 450000.00),
(8, '2026-06-04', 8, 1, 2500000.00),
(9, '2026-06-05', 9, 2, 600000.00),
(10, '2026-06-05', 10, 1, 700000.00),
(11, '2026-06-06', 1, 2, 900000.00),
(12, '2026-06-06', 2, 1, 1100000.00),
(13, '2026-06-07', 3, 2, 400000.00),
(14, '2026-06-07', 4, 1, 1300000.00),
(15, '2026-06-08', 5, 2, 1000000.00),
(16, '2026-06-08', 6, 1, 350000.00),
(17, '2026-06-09', 7, 2, 550000.00),
(18, '2026-06-09', 8, 1, 2200000.00),
(19, '2026-06-10', 9, 2, 650000.00),
(20, '2026-06-10', 10, 1, 750000.00),
(21, '2026-06-11', 1, 2, 950000.00),
(22, '2026-06-11', 2, 1, 1200000.00),
(23, '2026-06-12', 3, 2, 450000.00),
(24, '2026-06-12', 4, 1, 1400000.00),
(25, '2026-06-13', 5, 2, 1100000.00),
(26, '2026-06-13', 6, 1, 400000.00),
(27, '2026-06-14', 7, 2, 600000.00),
(28, '2026-06-14', 8, 1, 2300000.00),
(29, '2026-06-15', 9, 2, 700000.00),
(30, '2026-06-15', 10, 1, 800000.00),
(32, '0000-00-00', 0, 1, 0.00),
(33, '0000-00-00', 0, 1, 0.00),
(35, '0000-00-00', 0, 1, 0.00),
(36, '0000-00-00', 0, 1, 0.00),
(38, '0000-00-00', NULL, 1, 0.00),
(39, '0000-00-00', NULL, 1, 0.00),
(41, '0000-00-00', NULL, 1, 0.00),
(43, '0000-00-00', NULL, 1, 0.00),
(44, '0000-00-00', NULL, 1, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id` int(11) NOT NULL,
  `compra_id` int(11) NOT NULL,
  `motocicleta_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`id`, `compra_id`, `motocicleta_id`, `cantidad`, `subtotal`) VALUES
(1, 1, 1, 1, 3500000.00),
(2, 2, 2, 1, 2800000.00),
(3, 3, 8, 1, 1500000.00),
(4, 4, 9, 1, 1700000.00),
(5, 5, 6, 1, 1100000.00),
(6, 6, 12, 1, 1000000.00),
(7, 7, 14, 1, 950000.00),
(8, 8, 4, 1, 4200000.00),
(9, 9, 19, 1, 1200000.00),
(10, 10, 21, 1, 850000.00),
(11, 11, 29, 1, 600000.00),
(12, 12, 7, 1, 1800000.00),
(13, 13, 22, 1, 1600000.00),
(14, 14, 23, 1, 2500000.00),
(15, 15, 18, 1, 2800000.00),
(16, 16, 20, 1, 1900000.00),
(17, 17, 26, 1, 4500000.00),
(18, 18, 27, 1, 4000000.00),
(19, 19, 10, 1, 2000000.00),
(20, 20, 15, 1, 5500000.00),
(21, 21, 16, 1, 4800000.00),
(22, 22, 17, 1, 6500000.00),
(23, 23, 24, 1, 6000000.00),
(24, 24, 5, 1, 2200000.00),
(25, 25, 25, 1, 9500000.00),
(26, 26, 13, 1, 2700000.00),
(27, 27, 3, 1, 2600000.00),
(28, 28, 28, 1, 2600000.00),
(29, 29, 30, 1, 2800000.00),
(30, 30, 11, 1, 2400000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `motocicleta_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `venta_id`, `motocicleta_id`, `cantidad`, `subtotal`) VALUES
(1, 1, 1, 1, 35000000.00),
(2, 2, 2, 1, 18000000.00),
(3, 3, 3, 1, 26000000.00),
(4, 4, 4, 1, 42000000.00),
(5, 5, 5, 1, 22000000.00),
(6, 6, 6, 1, 11000000.00),
(7, 7, 7, 1, 18000000.00),
(8, 8, 8, 1, 15000000.00),
(9, 9, 9, 1, 17000000.00),
(10, 10, 10, 1, 20000000.00),
(11, 11, 11, 1, 24000000.00),
(12, 12, 12, 1, 10000000.00),
(13, 13, 13, 1, 27000000.00),
(14, 14, 14, 1, 9500000.00),
(15, 15, 15, 1, 55000000.00),
(16, 16, 16, 1, 48000000.00),
(17, 17, 17, 1, 65000000.00),
(18, 18, 18, 1, 28000000.00),
(19, 19, 19, 1, 12000000.00),
(20, 20, 20, 1, 19000000.00),
(21, 21, 21, 1, 8500000.00),
(22, 22, 22, 1, 16000000.00),
(23, 23, 23, 1, 25000000.00),
(24, 24, 24, 1, 60000000.00),
(25, 25, 25, 1, 95000000.00),
(26, 26, 26, 1, 45000000.00),
(27, 27, 27, 1, 40000000.00),
(28, 28, 28, 1, 26000000.00),
(29, 29, 29, 1, 6000000.00),
(30, 30, 30, 1, 28000000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motocicletas`
--

CREATE TABLE `motocicletas` (
  `id` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `fo_categoria` int(11) NOT NULL,
  `cilindraje` int(11) DEFAULT NULL,
  `precio` decimal(10,0) NOT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `motocicletas`
--

INSERT INTO `motocicletas` (`id`, `marca`, `modelo`, `fo_categoria`, `cilindraje`, `precio`, `stock`) VALUES
(1, 'Yamaha', 'MT-07', 1, 689, 35000000, 5),
(2, 'Honda', 'CB500F', 1, 471, 28000000, 8),
(3, 'Kawasaki', 'Ninja 400', 1, 399, 26000000, 3),
(4, 'Suzuki', 'V-Strom 650', 2, 645, 42000000, 2),
(5, 'BMW', 'G 310 R', 1, 313, 22000000, 10),
(6, 'Yamaha', 'XTZ 150', 2, 149, 11000000, 15),
(7, 'Honda', 'XRE 300', 2, 291, 18000000, 7),
(8, 'KTM', 'Duke 200', 1, 199, 15000000, 6),
(9, 'Bajaj', 'Dominar 400', 1, 373, 17000000, 12),
(10, 'Royal Enfield', 'Himalayan', 2, 411, 20000000, 4),
(11, 'Yamaha', 'R3', 1, 321, 24000000, 5),
(12, 'Honda', 'CB 190R', 1, 184, 10000000, 20),
(13, 'Kawasaki', 'Z400', 1, 399, 27000000, 3),
(14, 'Suzuki', 'GSX-S150', 1, 147, 9500000, 9),
(15, 'BMW', 'F 850 GS', 2, 853, 55000000, 2),
(16, 'Yamaha', 'MT-09', 1, 890, 48000000, 3),
(17, 'Honda', 'Africa Twin', 2, 1084, 65000000, 1),
(18, 'KTM', 'Adventure 390', 2, 373, 28000000, 4),
(19, 'Bajaj', 'Pulsar N250', 1, 249, 12000000, 15),
(20, 'Royal Enfield', 'Classic 350', 3, 349, 19000000, 5),
(21, 'Yamaha', 'Fz 2.0', 1, 149, 8500000, 25),
(22, 'Honda', 'CRF 250F', 2, 250, 16000000, 4),
(23, 'Kawasaki', 'Versys 300', 2, 296, 25000000, 3),
(24, 'Suzuki', 'V-Strom 1050', 2, 1037, 60000000, 1),
(25, 'BMW', 'S 1000 RR', 1, 999, 95000000, 2),
(26, 'Yamaha', 'Tenere 700', 2, 689, 45000000, 2),
(27, 'Honda', 'NC750X', 2, 745, 40000000, 3),
(28, 'KTM', 'RC 390', 1, 373, 26000000, 4),
(29, 'Bajaj', 'Discover 125', 1, 124, 6000000, 30),
(30, 'Royal Enfield', 'Interceptor 650', 3, 648, 28000000, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `telefono`, `email`, `direccion`) VALUES
(1, 'BMW Motorrad', '3106666666', 'ventas@bmw.com', 'Bogota'),
(2, 'Distribuidora Honda', '3102222222', 'ventas@honda.com', 'Medellin'),
(3, 'Ducati Colombia', '3107777777', 'info@ducati.com', 'Medellin'),
(4, 'Kawasaki Bogota', '3118888888', 'ventas@kawa.com', 'Bogota'),
(5, 'Yamaha Motors', '3129999999', 'contacto@yamaha.com', 'Cali'),
(6, 'Repuestos El Jefe', '3001112222', 'ventas@eljefe.com', 'Barranquilla'),
(7, 'Lubricantes Total', '3013334444', 'info@total.com', 'Cartagena'),
(8, 'Llantas Michelin', '3025556666', 'ventas@michelin.com', 'Bogota'),
(9, 'Baterias Bosch', '3037778888', 'contacto@bosch.com', 'Medellin'),
(10, 'Cascos AGV', '3049990000', 'ventas@agv.com', 'Cali'),
(11, 'Accesorios Pro', '3051113333', 'info@accpro.com', 'Pereira'),
(12, 'Frenos Brembo', '3062224444', 'contacto@brembo.com', 'Bogota'),
(13, 'Motul Colombia', '3073335555', 'ventas@motul.com', 'Bucaramanga'),
(14, 'Cadena DID', '3084446666', 'info@did.com', 'Medellin'),
(15, 'Filtros K&N', '3095557777', 'ventas@kn.com', 'Bogota'),
(16, 'Kit de Arrastre Ren', '3106668888', 'contacto@ren.com', 'Cali'),
(17, 'Luces LED Pro', '3117779999', 'ventas@ledpro.com', 'Manizales'),
(18, 'Guantes Alpinestars', '3128880000', 'info@alpine.com', 'Bogota'),
(19, 'Chaquetas Dainese', '3139991111', 'ventas@dainese.com', 'Medellin'),
(20, 'Espejos Sport', '3140002222', 'contacto@espejos.com', 'Barranquilla'),
(21, 'Tornilleria Fija', '3151113333', 'ventas@fija.com', 'Cucuta'),
(22, 'Pinturas Carro', '3162224444', 'info@pintura.com', 'Bogota'),
(23, 'Herramientas Bahco', '3173335555', 'ventas@bahco.com', 'Medellin'),
(24, 'Suspensiones Ohlins', '3184446666', 'contacto@ohlins.com', 'Cali'),
(25, 'Plasticos Moto', '3195557777', 'ventas@plastic.com', 'Bogota'),
(26, 'Escape Akrapovic', '3206668888', 'info@akra.com', 'Medellin'),
(27, 'Soportes Moto', '3217779999', 'ventas@soporte.com', 'Pasto'),
(28, 'Alarmas Positron', '3228880000', 'contacto@alarm.com', 'Bogota'),
(29, 'GPS Rastreo', '3239991111', 'ventas@gps.com', 'Medellin'),
(30, 'Servicio Mecanico Top', '3240002222', 'info@mecanico.com', 'Cali');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` date NOT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`id`, `titulo`, `descripcion`, `fecha`, `usuario_id`) VALUES
(1, 'Cierre de caja', 'Reporte del cierre diario', '2026-06-01', 1),
(2, 'Inventario bajo', 'Stock critico en llantas', '2026-06-02', 2),
(3, 'Venta cancelada', 'Cliente no concreto pago', '2026-06-03', 3),
(4, 'Mantenimiento', 'Revision tecnica realizada', '2026-06-04', 4),
(5, 'Actualizacion precios', 'Ajuste por inflacion', '2026-06-05', 5),
(6, 'Reporte ventas', 'Resumen semanal de ventas', '2026-06-06', 6),
(7, 'Nuevo proveedor', 'Registro de Ducati Colombia', '2026-06-07', 7),
(8, 'Incidente', 'Error en sistema de facturacion', '2026-06-08', 8),
(9, 'Entrega repuestos', 'Recibido paquete de filtros', '2026-06-09', 9),
(10, 'Capacitacion', 'Entrenamiento nuevo personal', '2026-06-10', 10),
(11, 'Auditoria', 'Revision de saldos', '2026-06-11', 11),
(12, 'Cliente nuevo', 'Registro cliente frecuente', '2026-06-12', 12),
(13, 'Queja', 'Reclamo por garantia', '2026-06-13', 13),
(14, 'Cambio aceite', 'Servicio realizado a moto', '2026-06-14', 14),
(15, 'Stock actualizado', 'Ingreso de nuevos cascos', '2026-06-15', 15),
(16, 'Backup', 'Respaldo base de datos', '2026-06-16', 16),
(17, 'Promocion', 'Inicio campaña de verano', '2026-06-17', 17),
(18, 'Devolucion', 'Frenos defectuosos', '2026-06-18', 18),
(19, 'Pago proveedor', 'Transferencia realizada', '2026-06-19', 19),
(20, 'Ajuste inventario', 'Correccion por conteo fisico', '2026-06-20', 20),
(21, 'Limpieza sistema', 'Eliminacion registros antiguos', '2026-06-21', 21),
(22, 'Visita tecnica', 'Revision de equipos', '2026-06-22', 22),
(23, 'Descuento', 'Cupon aplicado en venta', '2026-06-23', 23),
(24, 'Evento', 'Lanzamiento nueva marca', '2026-06-24', 24),
(25, 'Reporte fallas', 'Error en motor cliente', '2026-06-25', 25),
(26, 'Seguridad', 'Cambio contraseña admin', '2026-06-26', 26),
(27, 'Factura pendiente', 'Recordatorio enviado', '2026-06-27', 27),
(28, 'Registro garantia', 'Moto vendida recientemente', '2026-06-28', 28),
(29, 'Envio', 'Despacho hacia Medellin', '2026-06-29', 29),
(30, 'Resumen mensual', 'Cierre contable junio', '2026-06-30', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `identificacion` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `identificacion`, `nombre`, `password`, `rol`) VALUES
(1, '1001', 'Admin Principal', 'pass123', NULL),
(2, '1002', 'Vendedor 01', 'pass123', NULL),
(3, '1003', 'Vendedor 02', 'pass123', NULL),
(4, '1004', 'Carlos Ruiz', 'pass123', NULL),
(5, '1005', 'Maria Torres', 'pass123', NULL),
(6, '1006', 'Juan Perez', 'pass123', NULL),
(7, '1007', 'Elena Martinez', 'pass123', NULL),
(8, '1008', 'Diego Ramirez', 'pass123', NULL),
(9, '1009', 'Sofia Herrera', 'pass123', NULL),
(10, '1010', 'Javier Ortiz', 'pass123', NULL),
(11, '1011', 'Valentina Castro', 'pass123', NULL),
(12, '1012', 'Felipe Morales', 'pass123', NULL),
(13, '1013', 'Camila Vargas', 'pass123', NULL),
(14, '1014', 'Santiago Silva', 'pass123', NULL),
(15, '1015', 'Isabella Rojas', 'pass123', NULL),
(16, '1016', 'Mateo Mendoza', 'pass123', NULL),
(17, '1017', 'Lucia Paredes', 'pass123', NULL),
(18, '1018', 'Sebastiin Gil', 'pass123', NULL),
(19, '1019', 'Mariana Rios', 'pass123', NULL),
(20, '1020', 'Nicolas Vega', 'pass123', NULL),
(21, '1021', 'Gabriela Muñoz', 'pass123', NULL),
(22, '1022', 'Alejandro Rios', 'pass123', NULL),
(23, '1023', 'Daniela Paz', 'pass123', NULL),
(24, '1024', 'Tomas Duque', 'pass123', NULL),
(25, '1025', 'Clara Leon', 'pass123', NULL),
(26, '1026', 'Pablo Soles', 'pass123', NULL),
(27, '1027', 'Natalia Cruz', 'pass123', NULL),
(28, '1028', 'Hugo Diaz', 'pass123', NULL),
(29, '1029', 'Sara Luna', 'pass123', NULL),
(30, '1030', 'Oscar Rivas', 'pass123', NULL),
(33, '200', 'Ana Gomez', '', 'Vendedor'),
(37, '400', 'Andres Acevedo', '', 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `fecha`, `cliente_id`, `usuario_id`, `total`) VALUES
(1, '2026-06-01', 1, 1, 35000000.00),
(2, '2026-06-02', 2, 1, 18000000.00),
(3, '2026-06-02', 3, 2, 26000000.00),
(4, '2026-06-03', 4, 1, 42000000.00),
(5, '2026-06-04', 5, 2, 22000000.00),
(6, '2026-06-05', 6, 1, 11000000.00),
(7, '2026-06-06', 7, 2, 18000000.00),
(8, '2026-06-07', 8, 1, 15000000.00),
(9, '2026-06-08', 9, 2, 17000000.00),
(10, '2026-06-09', 10, 1, 20000000.00),
(11, '2026-06-10', 11, 2, 24000000.00),
(12, '2026-06-11', 12, 1, 10000000.00),
(13, '2026-06-12', 13, 2, 27000000.00),
(14, '2026-06-13', 14, 1, 9500000.00),
(15, '2026-06-14', 15, 2, 55000000.00),
(16, '2026-06-15', 16, 1, 48000000.00),
(17, '2026-06-16', 17, 2, 65000000.00),
(18, '2026-06-17', 18, 1, 28000000.00),
(19, '2026-06-18', 19, 2, 12000000.00),
(20, '2026-06-19', 20, 1, 19000000.00),
(21, '2026-06-20', 21, 2, 8500000.00),
(22, '2026-06-21', 22, 1, 16000000.00),
(23, '2026-06-22', 23, 2, 25000000.00),
(24, '2026-06-23', 24, 1, 60000000.00),
(25, '2026-06-24', 25, 2, 95000000.00),
(26, '2026-06-25', 26, 1, 45000000.00),
(27, '2026-06-26', 27, 2, 40000000.00),
(28, '2026-06-27', 28, 1, 26000000.00),
(29, '2026-06-28', 29, 2, 6000000.00),
(30, '2026-06-29', 30, 1, 28000000.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compra_id` (`compra_id`),
  ADD KEY `motocicleta_id` (`motocicleta_id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `motocicleta_id` (`motocicleta_id`);

--
-- Indices de la tabla `motocicletas`
--
ALTER TABLE `motocicletas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificacion` (`identificacion`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `motocicletas`
--
ALTER TABLE `motocicletas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`motocicleta_id`) REFERENCES `motocicletas` (`id`);

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`motocicleta_id`) REFERENCES `motocicletas` (`id`);

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
