-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: tienda_muebles
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `rol`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES (1,'Cliente Demo','cliente@ejemplo.com','cliente',NULL,'$2y$12$7ay1Xd8.FZiqmGRulB4VD.3PXolsh7LeX17P6We/jypqDCM149m/W',NULL,'2026-11-10 22:20:45','2026-11-10 22:20:45'),(2,'Admin Demo','admin@ejemplo.com','admin',NULL,'$2y$12$pM6ucLzfHtca5hc/GLiVcOgVFPS24LR7q.h.T6vZvuLi5zOWFNUZO',NULL,'2026-11-10 22:20:45','2026-11-10 22:20:45');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` (`id`, `nombre`, `created_at`, `updated_at`) VALUES (1,'Sofás','2026-11-10 22:20:45','2026-11-10 22:20:45'),(2,'Mesas','2026-11-10 22:20:45','2026-11-10 22:20:45'),(3,'Sillas','2026-11-10 22:20:45','2026-11-10 22:20:45'),(4,'Lámparas','2026-11-10 22:20:45','2026-11-10 22:20:45');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ambientes`
--

LOCK TABLES `ambientes` WRITE;
/*!40000 ALTER TABLE `ambientes` DISABLE KEYS */;
INSERT INTO `ambientes` (`id`, `nombre`, `created_at`, `updated_at`) VALUES (1,'Sala','2026-11-10 22:20:45','2026-11-10 22:20:45'),(2,'Dormitorio','2026-11-10 22:20:45','2026-11-10 22:20:45'),(3,'Cocina','2026-11-10 22:20:45','2026-11-10 22:20:45'),(4,'Oficina','2026-11-10 22:20:45','2026-11-10 22:20:45'),(5,'Exterior','2026-11-10 22:20:45','2026-11-10 22:20:45');
/*!40000 ALTER TABLE `ambientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `imagen`, `precio`, `stock`, `categoria_id`, `ambiente_id`, `created_at`, `updated_at`, `nombre_en`, `descripcion_en`) VALUES (1,'Sofá Nórdico 3 puestos','Mueble de la categoría Sofás, ideal para Sala.','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRqAt3inXQXUeJl7YAIa4Yiqh8ry6FMr7gKlQbF3IsnFQ&s=10',1890000.00,13,1,1,'2026-11-10 22:20:45','2026-11-10 22:20:45','3-seater duvet sofa','Furniture in the Sofas category, ideal for living rooms.'),(2,'Sofá Chaise Longue','Mueble de la categoría Sofás, ideal para Sala.','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSh6oVsuuhnxwhbYiPzNbWH_mYMbfNPVxbetuW-l_liUA&s=10',2350000.00,3,1,1,'2026-11-10 22:20:47','2026-11-10 22:20:47','Chaise Lounge','Furniture in the Sofas category, ideal for living rooms.'),(3,'Mesa de Centro Roble','Mueble de la categoría Mesas, ideal para Sala.','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRA1Z322ekhz_lCiMm4hb366PyjEdR6WPRoTKYQoIA2Rg&s=10',680000.00,19,2,1,'2026-11-10 22:20:48','2026-11-10 22:20:48','Oak coffee table','Furniture in the Tables category, ideal for the Living Room.'),(4,'Mesa Comedor Extensible','Mueble de la categoría Mesas, ideal para Cocina.','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQWuzmwK5zEvbbF5O3_t7u5VSoeEgiZFE5AW9YFuFkUbw&s=10',1450000.00,18,2,3,'2026-11-10 22:20:50','2026-11-10 22:20:50','Extendable Dining Table','Furniture in the Tables category, ideal for the Kitchen.'),(5,'Silla Escandinava','Mueble de la categoría Sillas, ideal para Sala.','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSgDzDQ2nnpe9ws6s8uwmp049DomKoeebElGYXKIPS0Sg&s=10',320000.00,15,3,1,'2026-11-10 22:20:51','2026-11-10 22:20:51','Scandinavian Chair','Furniture in the Chairs category, ideal for the Living Room.'),(6,'Silla de Oficina Ergonómica','Mueble de la categoría Sillas, ideal para Oficina.','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTMaUIwNt3OP8IFij6Oorny8A6lEYEMer9c9kJysDxQNA&s=10',590000.00,20,3,4,'2026-11-10 22:20:53','2026-11-10 22:20:53','Ergonomic Office Chair','Furniture in the Chairs category, ideal for Office.'),(7,'Lámpara de Piso Trípode','Mueble de la categoría Lámparas, ideal para Sala.','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8W6hsYPQGpAhjvZwY2x5rcJUmsZ6BrNONqGbuQztzbQ&s',240000.00,17,4,1,'2026-11-10 22:20:54','2026-11-10 22:20:54','Tripod Floor Flamp','Furniture in the Lamps category, ideal for the Living Room.'),(8,'Lámpara de Mesa Cerámica','Mueble de la categoría Lámparas, ideal para Dormitorio.','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8XMUN9glMSZy3FOaNGk07IHaB1FrafP-7KxQvGONonQ&s=10',130000.00,6,4,2,'2026-11-10 22:20:55','2026-11-10 22:20:55','Ceramic Table Lamp','Furniture in the Lamps category, ideal for the bedroom.'),(9,'Cama Doble Tapizada','Mueble de la categoría Sofás, ideal para Dormitorio.','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR87ovbCGeU1Ot7leoDnqpOaoqUGir5w7PR8LMiSy7ggQ&s=10',2100000.00,18,1,2,'2026-11-10 22:20:57','2026-11-10 22:20:57','Upholstered Double Bed','Furniture in the Sofas category, ideal for the bedroom.'),(10,'Set Silla y Mesa Exterior','Mueble de la categoría Sillas, ideal para Exterior.','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJo0kayh_vsAWDK4JzBk7aVKStKnjzZZvobG1nNoT8xw&s',990000.00,16,3,5,'2026-11-10 22:20:58','2026-11-10 22:20:58','Outdoor Chair and Table Set','Furniture in the Chairs category, ideal for Outdoor use.');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `especificaciones_muebles`
--

LOCK TABLES `especificaciones_muebles` WRITE;
/*!40000 ALTER TABLE `especificaciones_muebles` DISABLE KEYS */;
INSERT INTO `especificaciones_muebles` (`id`, `producto_id`, `material`, `color`, `ancho_cm`, `alto_cm`, `profundidad_cm`, `peso_kg`, `requiere_ensamblaje`, `garantia_meses`, `created_at`, `updated_at`) VALUES (1,1,'Tela','Gris',210.0,85.0,90.0,45.00,1,24,'2026-11-10 22:20:47','2026-11-10 22:20:47'),(2,2,'Cuero sintético','Café',260.0,80.0,160.0,60.00,1,24,'2026-11-10 22:20:48','2026-11-10 22:20:48'),(3,3,'Madera de roble','Natural',110.0,45.0,60.0,18.00,0,12,'2026-11-10 22:20:50','2026-11-10 22:20:50'),(4,4,'Madera MDF','Blanco',160.0,75.0,90.0,35.00,1,18,'2026-11-10 22:20:51','2026-11-10 22:20:51'),(5,5,'Madera y tela','Beige',55.0,80.0,55.0,6.00,1,12,'2026-11-10 22:20:53','2026-11-10 22:20:53'),(6,6,'Malla y metal','Negro',65.0,115.0,65.0,14.00,1,24,'2026-11-10 22:20:54','2026-11-10 22:20:54'),(7,7,'Madera y tela','Natural',40.0,150.0,40.0,4.00,1,12,'2026-11-10 22:20:55','2026-11-10 22:20:55'),(8,8,'Cerámica','Blanco',20.0,45.0,20.0,2.00,0,12,'2026-11-10 22:20:57','2026-11-10 22:20:57'),(9,9,'Tela','Azul petróleo',150.0,110.0,200.0,55.00,1,24,'2026-11-10 22:20:58','2026-11-10 22:20:58'),(10,10,'Aluminio y ratán','Gris oscuro',60.0,90.0,60.0,8.00,1,12,'2026-11-10 22:20:59','2026-11-10 22:20:59');
/*!40000 ALTER TABLE `especificaciones_muebles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` (`id`, `user_id`, `fecha`, `estado`, `total`, `created_at`, `updated_at`, `nombre_receptor`, `direccion_envio`, `ciudad_envio`, `telefono_contacto`, `certificado_serial`, `certificado_hash`, `certificado_bloque`, `certificado_emitido_en`) VALUES (1,1,'2026-11-05','entregado',17420000.00,'2026-11-10 22:20:59','2026-11-10 22:30:44',NULL,NULL,NULL,NULL,'MYD-PED-000001','ff2ef7ba286a21a227513b8b12617a69dd956302ec4886f1d46013160a837f89',6,'2026-11-10 22:30:44');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `detalle_pedidos`
--

LOCK TABLES `detalle_pedidos` WRITE;
/*!40000 ALTER TABLE `detalle_pedidos` DISABLE KEYS */;
INSERT INTO `detalle_pedidos` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`, `created_at`, `updated_at`) VALUES (1,1,1,4,1890000.00,'2026-11-10 22:20:59','2026-11-10 22:20:59'),(2,1,2,3,2350000.00,'2026-11-10 22:20:59','2026-11-10 22:20:59'),(3,1,3,2,680000.00,'2026-11-10 22:20:59','2026-11-10 22:20:59'),(4,1,4,1,1450000.00,'2026-11-10 22:20:59','2026-11-10 22:20:59');
/*!40000 ALTER TABLE `detalle_pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `resenas`
--

LOCK TABLES `resenas` WRITE;
/*!40000 ALTER TABLE `resenas` DISABLE KEYS */;
INSERT INTO `resenas` (`id`, `user_id`, `producto_id`, `calificacion`, `comentario`, `created_at`, `updated_at`) VALUES (1,1,1,5,'Buen producto, cumple lo esperado.','2026-11-10 22:20:59','2026-11-10 22:20:59'),(2,1,2,4,'Buen producto, cumple lo esperado.','2026-11-10 22:20:59','2026-11-10 22:20:59'),(3,1,3,5,'Buen producto, cumple lo esperado.','2026-11-10 22:20:59','2026-11-10 22:20:59'),(4,1,4,4,'Buen producto, cumple lo esperado.','2026-11-10 22:20:59','2026-11-10 22:20:59'),(5,1,5,4,'Buen producto, cumple lo esperado.','2026-11-10 22:20:59','2026-11-10 22:20:59'),(6,1,6,5,'Buen producto, cumple lo esperado.','2026-11-10 22:20:59','2026-11-10 22:20:59');
/*!40000 ALTER TABLE `resenas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-11  3:35:10
