-- MySQL dump 10.13  Distrib 8.0.13, for Win64 (x86_64)
--
-- Host: localhost    Database: liliana_player
-- ------------------------------------------------------
-- Server version	8.0.13

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'ADMIN'),(2,'USER');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `song`
--

LOCK TABLES `song` WRITE;
/*!40000 ALTER TABLE `song` DISABLE KEYS */;
INSERT INTO `song` VALUES (1,'123 Em Yêu Anh','Hạ Tử Linh, Giang Triều',NULL,'123-Em-Yêu-Anh-Hạ-Tử-Linh-Giang-Triều_1','China',3,'123 Em Yêu Anh _ 123我爱你 - Hạ Tử Linh, Giang Triều.mp3','Hạ Tử Linh, Giang Triều - 123 Em Yêu Anh.jpg','/api/song/album?file=Hạ Tử Linh, Giang Triều - 123 Em Yêu Anh.jpg','Tạ Anh Tú',NULL,0,'2021-06-09 19:21:29','2021-06-10 00:00:00'),(2,'Có chút ngọt ngào - 有点甜','汪苏泷,By2','万有引力','Có-chút-ngọt-ngào---有点甜-汪苏泷By2_2','China',23,'Có một chút ngọt ngào (有点甜)_汪蘇瀧_-4563536.mp3','汪苏泷,By2 - Có chút ngọt ngào - 有点甜.jpg','/api/song/album?file=汪苏泷,By2 - Có chút ngọt ngào - 有点甜.jpg',NULL,NULL,0,'2021-06-09 19:21:30','2021-06-10 00:00:00'),(3,'Gặp Em Đúng Lúc  剛好遇見你','Luân Tang, Tiêu Ức Tình, Huyền Thương',NULL,'Gặp-Em-Đúng-Lúc--剛好遇見你-Luân-Tang-Tiêu-Ức-Tình-Huyền-Thương_3','China',71,'Gặp Em Đúng Lúc _ 剛好遇見你 - Luân Tang, Tiêu Ức Tình, Huyền Thương.mp3','Luân Tang, Tiêu Ức Tình, Huyền Thương - Gặp Em Đúng Lúc  剛好遇見你.jpg','/api/song/album?file=Luân Tang, Tiêu Ức Tình, Huyền Thương - Gặp Em Đúng Lúc  剛好遇見你.jpg',NULL,NULL,0,'2021-06-09 19:21:30','2021-06-10 00:00:00'),(4,'Học Mèo Kêu (学猫叫)','Tiểu Phan Phan, Tiểu Phong Phong','Học Mèo Kêu (Single)','Học-Mèo-Kêu-(学猫叫)-Tiểu-Phan-Phan-Tiểu-Phong-Phong_4','China',35,'Học Mèo Kêu (学猫叫) - Tiểu Phan Phan, Tiểu Phong Phong.mp3','Tiểu Phan Phan, Tiểu Phong Phong - Học Mèo Kêu (学猫叫).jpg','/api/song/album?file=Tiểu Phan Phan, Tiểu Phong Phong - Học Mèo Kêu (学猫叫).jpg','Tiểu Phan Phan, Tiểu Phong Phong - Học Mèo Kêu (学猫叫).trc',NULL,0,'2021-06-09 19:21:30','2021-06-10 00:00:00'),(5,'Tan Dong Song Ly Biet (Ost)','Trieu Vy','NhacCuaTui','Tan-Dong-Song-Ly-Biet-(Ost)-Trieu-Vy','China',2,'TanDongSongLyBietOst-TrieuVy_7req.mp3',NULL,NULL,NULL,NULL,0,'2021-06-09 19:21:31','2021-12-03 00:51:39'),(6,'寵愛','TFBoys','å¯µæ / Sung Ai (EP)','寵愛-TFBoys_6','China',18,'寵愛 Sủng Ái_TFBoys_-1075255830.mp3','TFBoys - 寵愛.jpg','/api/song/album?file=TFBoys - 寵愛.jpg','TFBoys - 寵愛.trc',NULL,0,'2021-06-09 19:21:31','2021-06-10 00:00:00'),(7,'All Falls Down','Alan Walker, Noah Cyrus, Digital Farm Animals','All Falls Down (Single)','All-Falls-Down-Alan-Walker-Noah-Cyrus-Digital-Farm-Animals_7','Electronic Dance',43,'All Falls Down - Alan Walker, Noah Cyrus, Digital Farm Animals.MP3','Alan Walker, Noah Cyrus, Digital Farm Animals - All Falls Down.jpg','/api/song/album?file=Alan Walker, Noah Cyrus, Digital Farm Animals - All Falls Down.jpg','Alan Walker, Noah Cyrus, Digital Farm Animals - All Falls Down.trc',NULL,0,'2021-06-09 19:21:31','2021-06-10 00:00:00'),(8,'Different World','Alan Walker, K-391, Sofia Carson, CORSAK','Different World  (Single)','Different-World-Alan-Walker-K-391-Sofia-Carson-CORSAK_8','Electronic Dance',70,'Different World - Alan Walker.mp3','Alan Walker, K-391, Sofia Carson, CORSAK - Different World.jpg','/api/song/album?file=Alan Walker, K-391, Sofia Carson, CORSAK - Different World.jpg','Alan Walker, K-391, Sofia Carson, CORSAK - Different World.trc',NULL,0,'2021-06-09 19:21:31','2021-06-10 00:00:00'),(9,'Faded','Alan Walker','mp3.zing.vn','Faded-Alan-Walker_9','Electronic Dance',15,'Faded - Alan Walker.mp3',NULL,NULL,'Alan Walker - Faded.lrc',NULL,0,'2021-06-09 19:21:31','2021-06-10 00:00:00'),(10,'Fly Away','TheFatRat, Anjulie','Fly Away','Fly-Away-TheFatRat-Anjulie_10','Electronic Dance',94,'Fly Away - TheFatRat, Anjulie - Zing MP3.MP3','TheFatRat, Anjulie - Fly Away.jpg','/api/song/album?file=TheFatRat, Anjulie - Fly Away.jpg','TheFatRat, Anjulie - Fly Away.trc',NULL,0,'2021-06-09 19:21:32','2021-06-10 00:00:00'),(11,'Lily','Alan Walker, K-391, Emelie Hollow','Different World','Lily-Alan-Walker-K-391-Emelie-Hollow_11','Electronic Dance',109,'Lily - Alan Walker, K-391, Emelie Hollow.mp3','Alan Walker, K-391, Emelie Hollow - Lily.jpg','/api/song/album?file=Alan Walker, K-391, Emelie Hollow - Lily.jpg','Alan Walker, K-391, Emelie Hollow - Lily.trc',NULL,0,'2021-06-09 19:21:32','2021-06-10 00:00:00'),(12,'Monody','TheFatRat, Laura Brehm','NhacCuaTui.com','Monody-TheFatRat-Laura-Brehm_12','Electronic Dance',14,'Monody_128k.mp3','TheFatRat, Laura Brehm - Monody.jpg','/api/song/album?file=TheFatRat, Laura Brehm - Monody.jpg','TheFatRat, Laura Brehm - Monody.trc',NULL,0,'2021-06-09 19:21:32','2021-06-10 00:00:00'),(13,'On My Way','Alan Walker, Sabrina Carpenter, Farruko','On My Way (Single)','On-My-Way-Alan-Walker-Sabrina-Carpenter-Farruko_13','Electronic Dance',23,'On My Way - Alan Walker, Sabrina Carpenter, Farruko.mp3','Alan Walker, Sabrina Carpenter, Farruko - On My Way.jpg','/api/song/album?file=Alan Walker, Sabrina Carpenter, Farruko - On My Way.jpg','Alan Walker, Sabrina Carpenter, Farruko - On My Way.lrc',NULL,0,'2021-06-09 19:21:32','2021-06-10 00:00:00'),(14,'Save Me','DEAMN','Save Me (Single)','Save-Me-DEAMN_14','Electronic Dance',78,'Save Me - DEAMN.MP3','DEAMN - Save Me.jpg','/api/song/album?file=DEAMN - Save Me.jpg','DEAMN - Save Me.trc',NULL,0,'2021-06-09 19:21:32','2021-06-10 00:00:00'),(15,'Something Just Like This','J.Fla',NULL,'Something-Just-Like-This-J.Fla_15','Electronic Dance',22,'Something Just Like This - J_Fla.mp3','J.Fla - Something Just Like This.jpg','/api/song/album?file=J.Fla - Something Just Like This.jpg','J.Fla - Something Just Like This.trc',NULL,0,'2021-06-09 19:21:32','2021-06-10 00:00:00'),(16,'The Spectre','Alan Walker','The Spectre (Single)','The-Spectre-Alan-Walker_16','Electronic Dance',13,'The Spectre - Alan Walker.MP3','Alan Walker - The Spectre.jpg','/api/song/album?file=Alan Walker - The Spectre.jpg','Alan Walker - The Spectre.trc',NULL,0,'2021-06-09 19:21:33','2021-06-10 00:00:00'),(17,'Never Be Alone','TheFatRat','Never Be Alone - Single','Never-Be-Alone-TheFatRat_17','Electronic Dance',18,'Thefatrat - Never Be Alone.mp3',NULL,NULL,'TheFatRat - Never Be Alone.trc',NULL,0,'2021-06-09 19:21:33','2021-06-10 00:00:00'),(18,'Unity','Thefatrat','Unity (Single)','Unity-Thefatrat_18','Electronic Dance',23,'Unity - TheFatRat.MP3','Thefatrat - Unity.jpg','/api/song/album?file=Thefatrat - Unity.jpg','Thefatrat - Unity.trc',NULL,0,'2021-06-09 19:21:33','2021-06-10 00:00:00'),(19,'Aloha','Cool','NhacCuaTui','Aloha-Cool_19','Korean',34,'Aloha-Cool_6d76.mp3',NULL,NULL,NULL,NULL,0,'2021-06-09 19:21:33','2021-06-10 00:00:00'),(20,'Because I\'m Stupid','SS501','NhacCuaTui.com','Because-I\'m-Stupid-SS501_20','Korean',3,'BecauseImStupid-SS501-205763.mp3','SS501 - Because I\'m Stupid.jpg','/api/song/album?file=SS501 - Because I\'m Stupid.jpg','SS501 - Because I\'m Stupid.lrc',NULL,0,'2021-06-09 19:21:33','2021-06-10 00:00:00'),(21,'Blue','BIGBANG','mp3.zing.vn','Blue-BIGBANG_21','Korean',5,'Blue - BIGBANG.mp3',NULL,NULL,'BIGBANG - Blue.trc',NULL,1,'2021-06-09 19:21:34','2021-06-10 00:00:00'),(22,'Destiny','Why','Full House OST','Destiny-Why_22','Korean',33,'Fate (Full House OST)_Why_-4676134.mp3','Why - Destiny.jpg','/api/song/album?file=Why - Destiny.jpg',NULL,NULL,0,'2021-06-09 19:21:34','2021-06-10 00:00:00'),(23,'Haru Haru','Big Bang','mp3.zing.vn','Haru-Haru-Big-Bang_23','Korean',21,'Haru Haru - BIGBANG.mp3',NULL,NULL,'Big Bang - Haru Haru.trc',NULL,0,'2021-06-09 19:21:34','2021-06-10 00:00:00'),(24,'Stand By Me','Shinee','mp3.zing.vn','Stand-By-Me-Shinee_24','Korean',2,'Stand By Me - Shinee.mp3','Shinee - Stand By Me.jpg','/api/song/album?file=Shinee - Stand By Me.jpg','Shinee - Stand By Me.trc',NULL,0,'2021-06-09 19:21:34','2021-06-10 00:00:00'),(25,'Tonight','Big Bang','NhacCuaTui.Com','Tonight-Big-Bang_25','Korean',23,'Tonight-BigBang_xnpc.mp3',NULL,NULL,'Big Bang - Tonight.trc',NULL,0,'2021-06-09 19:21:34','2021-06-10 00:00:00'),(26,'Way Back Home','Shaun','Take (EP)','Way-Back-Home-Shaun_26','Korean',13,'Way Back Home - Shaun.mp3','Shaun - Way Back Home.jpg','/api/song/album?file=Shaun - Way Back Home.jpg','Shaun - Way Back Home.trc',NULL,0,'2021-06-09 19:21:35','2021-06-10 00:00:00'),(27,'Attention','Charlie Puth','Voicenotes','Attention-Charlie-Puth_27','US - UK',41,'Attention - Charlie Puth.mp3','Charlie Puth - Attention.jpg','/api/song/album?file=Charlie Puth - Attention.jpg','Charlie Puth - Attention.trc',NULL,0,'2021-06-09 19:21:35','2021-06-10 00:00:00'),(28,'Baby','Justin Bieber','My Worlds Acoustic - Nhac.vui.vn','Baby-Justin-Bieber_28','US - UK',3,'Baby.mp3','Justin Bieber - Baby.jpg','/api/song/album?file=Justin Bieber - Baby.jpg','Justin Bieber - Baby.trc',NULL,0,'2021-06-09 19:21:35','2021-06-10 00:00:00'),(29,'Comethru','Jeremy Zucker','','Comethru-Jeremy-Zucker_29','US - UK',17,'Comethru - Jeremy Zucker - Nhac_vn_128K.mp3',NULL,NULL,NULL,NULL,0,'2021-06-09 19:21:35','2021-06-10 00:00:00'),(30,'Counting Stars','OneRepublic','mp3.zing.vn','Counting-Stars-OneRepublic_30','US - UK',8,'Counting Stars - OneRepublic.mp3',NULL,NULL,'OneRepublic - Counting Stars.trc',NULL,0,'2021-06-09 19:21:35','2021-06-10 00:00:00'),(31,'Cry On My Shoulder','Super Stars','mp3.zing.vn','Cry-On-My-Shoulder-Super-Stars_31','US - UK',11,'Cry On My Shoulder - Super Stars.mp3',NULL,NULL,NULL,NULL,0,'2021-06-09 19:21:35','2021-06-10 00:00:00'),(32,'It\'s Not Goodbye','','From The Inside','It\'s-Not-Goodbye_32','US - UK',33,'It\'s Not Goodbye-Laura Pausini_[Nhacso.Net].mp3',NULL,NULL,NULL,NULL,0,'2021-06-09 19:21:36','2021-06-10 00:00:00'),(33,'Just Give Me A Reason','Pink,Nate Ruess','mp3.zing.vn','Just-Give-Me-A-Reason-PinkNate-Ruess_33','US - UK',12,'Just Give Me A Reason - Pink Nate Ruess.mp3',NULL,NULL,'Pink,Nate Ruess - Just Give Me A Reason.trc',NULL,1,'2021-06-09 19:21:36','2021-06-10 00:00:00'),(34,'La La La','Naughty Boy,Sam Smith','mp3.zing.vn','La-La-La-Naughty-BoySam-Smith_34','US - UK',3,'La La La - Naughty Boy Sam Smith.mp3',NULL,NULL,'Naughty Boy,Sam Smith - La La La.trc',NULL,1,'2021-06-09 19:21:36','2021-06-10 00:00:00'),(35,'Love Me Like You Do','Ellie Goulding','mp3.zing.vn','Love-Me-Like-You-Do-Ellie-Goulding_35','US - UK',8,'Love Me Like You Do - Ellie Goulding _ Bài hát, lyrics.mp3',NULL,NULL,'Ellie Goulding - Love Me Like You Do.lrc',NULL,0,'2021-06-09 19:21:36','2021-06-10 00:00:00'),(36,'Take Me To Your Heart','Michael Learns To Rock','mp3.zing.vn','Take-Me-To-Your-Heart-Michael-Learns-To-Rock_36','US - UK',32,'Michael Learns To Rock - Take Me To Your Heart.mp3',NULL,NULL,'Michael Learns To Rock - Take Me To Your Heart.trc',NULL,0,'2021-06-09 19:21:36','2021-06-10 00:00:00'),(37,'Only Love','Trademark','mp3.zing.vn','Only-Love-Trademark_37','US - UK',58,'Only Love_Trademark_-1073773604.mp3',NULL,NULL,'Trademark - Only Love.trc',NULL,0,'2021-06-09 19:21:36','2021-06-10 00:00:00'),(38,'Season In The Sun','Westlife','Westlife [Germany Bonus Tracks]','Season-In-The-Sun-Westlife_38','US - UK',37,'Season In The Sun -Westlife_[Nhacso.Net].mp3','Westlife - Season In The Sun.jpg','/api/song/album?file=Westlife - Season In The Sun.jpg','Westlife - Season In The Sun.trc',NULL,0,'2021-06-09 19:21:37','2021-06-10 00:00:00'),(39,'See You Again','Wiz Khalifa,Charlie Puth','Furious 7 OST','See-You-Again-Wiz-KhalifaCharlie-Puth_39','US - UK',36,'See You Again_Wiz Khalifa, Charlie Puth_-1075239629.mp3','Wiz Khalifa,Charlie Puth - See You Again.jpg','/api/song/album?file=Wiz Khalifa,Charlie Puth - See You Again.jpg','Wiz Khalifa,Charlie Puth - See You Again.trc',NULL,0,'2021-06-09 19:21:37','2021-06-10 00:00:00'),(40,'Señorita','Shawn Mendes,Camila Cabello','Señorita (Single)','Señorita-Shawn-MendesCamila-Cabello_40','US - UK',23,'Senorita - Shawn Mendes Camila Cabello.mp3','Shawn Mendes,Camila Cabello - Señorita.jpg','/api/song/album?file=Shawn Mendes,Camila Cabello - Señorita.jpg','Shawn Mendes,Camila Cabello - Señorita.trc',NULL,0,'2021-06-09 19:21:37','2021-06-10 00:00:00'),(41,'The Day You Went Away','M2M','','The-Day-You-Went-Away-M2M_41','US - UK',14,'The Day You Went Away -M2M_[Nhacso.Net].mp3','M2M - The Day You Went Away.jpg','/api/song/album?file=M2M - The Day You Went Away.jpg','M2M - The Day You Went Away.trc',NULL,0,'2021-06-09 19:21:37','2021-06-10 00:00:00'),(42,'We Don\'t Talk Anymore','Charlie Puth,Selena Gomez','Nine Track Mind','We-Don\'t-Talk-Anymore-Charlie-PuthSelena-Gomez_42','US - UK',32,'We Don\'t Talk Anymore_Charlie Puth, Selena Gomez_-1075465009.mp3',NULL,NULL,NULL,NULL,0,'2021-06-09 19:21:37','2021-06-10 00:00:00'),(43,'Why Not Me','Enrique Iglesias','mp3.zing.vn','Why-Not-Me-Enrique-Iglesias_43','US - UK',12,'Why_Not_Me_-_Enrique_Iglesias.mp3',NULL,NULL,NULL,NULL,0,'2021-06-09 19:21:37','2021-06-10 00:00:00'),(44,'Ai Là Người Thương Em','Quân A.P',NULL,'Ai-Là-Người-Thương-Em-Quân-A.P_44','Vietnam',58,'Ai Là Người Thương Em_Quân A.P_-1079249721.mp3','Quân A.P - Ai Là Người Thương Em.jpg','/api/song/album?file=Quân A.P - Ai Là Người Thương Em.jpg','Quân A.P - Ai Là Người Thương Em.trc',NULL,0,'2021-06-09 19:21:38','2021-06-10 00:00:00'),(45,'Anh Nhà Ở Đâu Thế?','AMEE, B Ray','Anh Nhà Ở Đâu Thế? (Single)','Anh-Nhà-Ở-Đâu-Thế-AMEE-B-Ray_45','Vietnam',11,'Anh Nhà Ở Đâu Thế _AMEE, B Ray_-1079215630.mp3','Anh Nhà Ở Đâu Thế _AMEE, B Ray_-1079215630.jpg','/api/song/album?file=Anh Nhà Ở Đâu Thế _AMEE, B Ray_-1079215630.jpg',NULL,NULL,0,'2021-06-09 19:21:38','2021-06-10 00:00:00'),(46,'Bạc Phận','Jack, K-ICM','Bạc Phận (Single)','Bạc-Phận-Jack-K-ICM_46','Vietnam',24,'Bạc Phận_Jack, K-ICM_-1079227190.mp3','Jack, K-ICM - Bạc Phận.jpg','/api/song/album?file=Jack, K-ICM - Bạc Phận.jpg','Jack, K-ICM - Bạc Phận.trc',NULL,0,'2021-06-09 19:21:38','2021-06-10 00:00:00'),(47,'Chúng Ta Không Thuộc Về Nhau','Sơn Tùng M-TP','Chúng Ta Không Thuộc Về Nhau (Single)','Chúng-Ta-Không-Thuộc-Về-Nhau-Sơn-Tùng-M-TP_47','Vietnam',12,'Chúng Ta Không Thuộc Về Nhau_Sơn Tùng M-TP_-6152407.mp3',NULL,NULL,NULL,NULL,1,'2021-06-09 19:21:38','2021-06-10 00:00:00'),(48,'Có Em Chờ','Min, Mr.A','Có Em Chờ (Single)','Có-Em-Chờ-Min-Mr.A_48','Vietnam',9,'Có Em Chờ_Min, Mr.A_-1076227401.mp3','Min, Mr.A - Có Em Chờ.jpg','/api/song/album?file=Min, Mr.A - Có Em Chờ.jpg','Min, Mr.A - Có Em Chờ.trc',NULL,0,'2021-06-09 19:21:38','2021-06-10 00:00:00'),(49,'Cảm Giác Lúc Ấy Sẽ Ra Sao','Lou Hoàng','Cảm Giác Lúc Ấy Sẽ Ra Sao (Single)','Cảm-Giác-Lúc-Ấy-Sẽ-Ra-Sao-Lou-Hoàng_49','Vietnam',65,'Cảm Giác Lúc Ấy Sẽ Ra Sao_Lou Hoàng_-1079171754.mp3','Lou Hoàng - Cảm Giác Lúc Ấy Sẽ Ra Sao.jpg','/api/song/album?file=Lou Hoàng - Cảm Giác Lúc Ấy Sẽ Ra Sao.jpg','Lou Hoàng - Cảm Giác Lúc Ấy Sẽ Ra Sao.lrc',NULL,0,'2021-06-09 19:21:39','2021-06-10 00:00:00'),(50,'Danh Cho Em','Hoang Ton','mp3.zing.vn','Danh-Cho-Em-Hoang-Ton_50','Vietnam',26,'Danh Cho Em - Hoang Ton.mp3',NULL,NULL,'Hoang Ton - Danh Cho Em.lrc',NULL,0,'2021-06-09 19:21:39','2021-06-10 00:00:00'),(51,'Em Cua Ngay Hom Qua','Son Tung M-TP','mp3.zing.vn','Em-Cua-Ngay-Hom-Qua-Son-Tung-M-TP_51','Vietnam',21,'Em Cua Ngay Hom Qua - Son Tung M TP.mp3',NULL,NULL,'Son Tung M-TP - Em Cua Ngay Hom Qua.lrc',NULL,0,'2021-06-09 19:21:39','2021-06-10 00:00:00'),(52,'Em Khong Quay Ve','Hoang Ton,Yanbi','mp3.zing.vn','Em-Khong-Quay-Ve-Hoang-TonYanbi_52','Vietnam',14,'Em Khong Quay Ve - Hoang Ton Yanbi.mp3',NULL,NULL,'Hoang Ton,Yanbi - Em Khong Quay Ve.trc',NULL,0,'2021-06-09 19:21:39','2021-06-10 00:00:00'),(53,'Không Phải Dạng Vừa Đâu','Sơn Tùng (M-TP)','NhacCuaTui.Com','Không-Phải-Dạng-Vừa-Đâu-Sơn-Tùng-(M-TP)_53','Vietnam',22,'KhongPhaiDangVuaDau-SonTungMTP-3753840.mp3',NULL,NULL,'Sơn Tùng (M-TP) - Không Phải Dạng Vừa Đâu.lrc',NULL,0,'2021-06-09 19:21:39','2021-06-10 00:00:00'),(54,'Là Bạn Không Thể Yêu','Lou Hoàng','Là Bạn Không Thể Yêu (Single)','Là-Bạn-Không-Thể-Yêu-Lou-Hoàng_54','Vietnam',180,'Là Bạn Không Thể Yêu_Lou Hoàng_-1079351290.mp3','Lou Hoàng - Là Bạn Không Thể Yêu.jpg','/api/song/album?file=Lou Hoàng - Là Bạn Không Thể Yêu.jpg','Lou Hoàng - Là Bạn Không Thể Yêu.trc',NULL,0,'2021-06-09 19:21:39','2021-06-10 00:00:00'),(55,'Là Con Gái Thật Tuyệt (Trái Tim Có Nắng OST)','Phạm Anh Tuấn',NULL,'Là-Con-Gái-Thật-Tuyệt-(Trái-Tim-Có-Nắng-OST)-Phạm-Anh-Tuấn_55','Vietnam',21,'Là Con Gái Thật Tuyệt (Trái Tim Có Nắng OST) - Phạm Anh Tuấn.mp3',NULL,NULL,'Phạm Anh Tuấn - Là Con Gái Thật Tuyệt (Trái Tim Có Nắng OST).trc',NULL,0,'2021-06-09 19:21:40','2021-06-10 00:00:00'),(56,'Minh La Gi Cua Nhau','Lou Hoang','NhacCuaTui.Com','Minh-La-Gi-Cua-Nhau-Lou-Hoang_56','Vietnam',36,'MinhLaGiCuaNhau-LouHoang-4592687.mp3',NULL,NULL,'Lou Hoang - Minh La Gi Cua Nhau.trc',NULL,0,'2021-06-09 19:21:40','2021-06-10 00:00:00'),(57,'Mua Xa Nhau','Emily','mp3.zing.vn','Mua-Xa-Nhau-Emily_57','Vietnam',14,'Mua Xa Nhau - Emily.mp3',NULL,NULL,NULL,NULL,0,'2021-06-09 19:21:40','2021-06-10 00:00:00'),(58,'Mặt Trời Của Em','Phương Ly, JustaTee','Mặt Trời Của Em (Single)','Mặt-Trời-Của-Em-Phương-Ly-JustaTee_58','Vietnam',19,'Mặt Trời Của Em_Phương Ly, JustaTee_-1076420203.mp3','Phương Ly, JustaTee - Mặt Trời Của Em.jpg','/api/song/album?file=Phương Ly, JustaTee - Mặt Trời Của Em.jpg','Phương Ly, JustaTee - Mặt Trời Của Em.trc',NULL,0,'2021-06-09 19:21:40','2021-06-10 00:00:00'),(59,'Người Ấy','Trịnh Thăng Bình','NhacCuaTui.Com','Người-Ấy-Trịnh-Thăng-Bình_59','Vietnam',19,'NguoiAy-TrinhThangBinh_4er3q.mp3',NULL,NULL,'Trịnh Thăng Bình - Người Ấy.trc',NULL,0,'2021-06-09 19:21:40','2021-06-10 00:00:00'),(60,'Nơi Này Có Anh','Sơn Tùng M-TP','Nơi Này Có Anh (Single)','Nơi-Này-Có-Anh-Sơn-Tùng-M-TP_60','Vietnam',32,'Nơi Này Có Anh_Sơn Tùng M-TP_-1075841896.mp3','Sơn Tùng M-TP - Nơi Này Có Anh.jpg','/api/song/album?file=Sơn Tùng M-TP - Nơi Này Có Anh.jpg',NULL,NULL,0,'2021-06-09 19:21:41','2021-06-10 00:00:00'),(61,'Phía Sau Một Cô Gái','Soobin Hoàng Sơn','Phía Sau Một Cô Gái','Phía-Sau-Một-Cô-Gái-Soobin-Hoàng-Sơn_61','Vietnam',46,'Phía Sau Một Cô Gái_Soobin Hoàng Sơn.mp3','Soobin Hoàng Sơn - Phía Sau Một Cô Gái.jpg','/api/song/album?file=Soobin Hoàng Sơn - Phía Sau Một Cô Gái.jpg','Soobin Hoàng Sơn - Phía Sau Một Cô Gái.trc',NULL,0,'2021-06-09 19:21:41','2021-06-10 00:00:00'),(62,'She Neva Knows ( Original )','JustaTee','mp3.zing.vn','She-Neva-Knows-(-Original-)-JustaTee_62','Vietnam',14,'She Neva Knows - JustaTee.mp3',NULL,NULL,'JustaTee - She Neva Knows ( Original ).trc',NULL,0,'2021-06-09 19:21:41','2021-06-10 00:00:00'),(63,'Sóng Gió','Jack, K-ICM','Sóng Gió (Single)','Sóng-Gió-Jack-K-ICM_63','Vietnam',81,'Song Gio_Jack, K-ICM.mp3','Jack, K-ICM - Sóng Gió.jpg','/api/song/album?file=Jack, K-ICM - Sóng Gió.jpg','Jack, K-ICM - Sóng Gió.trc',NULL,0,'2021-06-09 19:21:41','2021-06-10 00:00:00'),(64,'Thu Cuoi','Yanbi,Mr T,Hang BingBoong','mp3.zing.vn','Thu-Cuoi-YanbiMr-THang-BingBoong_64','Vietnam',27,'Thu Cuoi - Yanbi Mr T Hang BingBoong.mp3',NULL,NULL,'Yanbi,Mr T,Hang BingBoong - Thu Cuoi.trc',NULL,0,'2021-06-09 19:21:41','2021-06-10 00:00:00'),(65,'That Tinh','Trinh Dinh Quang','mp3.zing.vn','That-Tinh-Trinh-Dinh-Quang_65','Vietnam',12,'Thất Tình_Trịnh Đình Quang_-1075399196.mp3',NULL,NULL,'Trinh Dinh Quang - That Tinh.trc',NULL,0,'2021-06-09 19:21:41','2021-06-10 00:00:00'),(66,'Tinh Yeu Mang Theo','Nhat Tinh Anh','mp3.zing.vn','Tinh-Yeu-Mang-Theo-Nhat-Tinh-Anh_66','Vietnam',25,'Tinh Yeu Mang Theo - Nhat Tinh Anh.mp3',NULL,NULL,NULL,NULL,0,'2021-06-09 19:21:42','2021-06-10 00:00:00'),(67,'Tinh Yeu Mau Nang','Doan Thi Thuy Trang,BigDaddy','mp3.zing.vn','Tinh-Yeu-Mau-Nang-Doan-Thi-Thuy-TrangBigDaddy_67','Vietnam',22,'Tinh Yeu Mau Nang - Doan Thuy Trang BigDaddy.mp3',NULL,NULL,'Doan Thi Thuy Trang,BigDaddy - Tinh Yeu Mau Nang.trc',NULL,0,'2021-06-09 19:21:42','2021-06-10 00:00:00'),(68,'Xin Anh Dung','LK Emily JustaTee',NULL,'Xin-Anh-Dung-LK-Emily-JustaTee','Vietnam',9,'Xin Anh Dung - LK Emily JustaTee.mp3','LK Emily JustaTee - Xin Anh Dung_1624786879.jpg','/api/song/picture?file=LK Emily JustaTee - Xin Anh Dung_1624786879.jpg',NULL,NULL,1,'2021-06-09 19:21:42','2021-12-04 12:06:50'),(69,'Xin Đừng Lặng Im','Soobin Hoàng Sơn','Xin Đừng Lặng Im (Single)','Xin-Đừng-Lặng-Im-Soobin-Hoàng-Sơn_69','Vietnam',16,'Xin Đừng Lặng Im_Soobin Hoàng Sơn_-1076323736.mp3','Soobin Hoàng Sơn - Xin Đừng Lặng Im.jpg','/api/song/album?file=Soobin Hoàng Sơn - Xin Đừng Lặng Im.jpg','Soobin Hoàng Sơn - Xin Đừng Lặng Im.lrc',NULL,0,'2021-06-09 19:21:42','2021-06-10 00:00:00'),(70,'Yêu Là \"Tha Thu\" (Em Chưa 18 OST)','OnlyC',NULL,'Yêu-Là-\"Tha-Thu\"-(Em-Chưa-18-OST)-OnlyC_70','Vietnam',45,'Yêu Là Tha Thu (Em Chưa 18 OST)_OnlyC_-1076121946.mp3','Yêu Là Tha Thu (Em Chưa 18 OST)_OnlyC_-1076121946.jpg','/api/song/album?file=Yêu Là Tha Thu (Em Chưa 18 OST)_OnlyC_-1076121946.jpg',NULL,NULL,0,'2021-06-09 19:21:42','2021-06-10 00:00:00'),(71,'Đau Để Trưởng Thành','OnlyC','Đau Để Trưởng Thành (Single)','Đau-Để-Trưởng-Thành-OnlyC_71','Vietnam',53,'Đau Để Trưởng Thành_OnlyC_-1079258980.mp3','OnlyC - Đau Để Trưởng Thành.jpg','/api/song/album?file=OnlyC - Đau Để Trưởng Thành.jpg','OnlyC - Đau Để Trưởng Thành.lrc',NULL,0,'2021-06-09 19:21:43','2021-06-10 00:00:00'),(72,'Dem Ngay Xa Em','OnlyC,Lou Hoang','mp3.zing.vn','Dem-Ngay-Xa-Em-OnlyCLou-Hoang_72','Vietnam',86,'Đếm Ngày Xa Em_OnlyC, Lou Hoàng_-1075507785.mp3','OnlyC,Lou Hoang - Dem Ngay Xa Em.jpg','/api/song/album?file=OnlyC,Lou Hoang - Dem Ngay Xa Em.jpg','OnlyC,Lou Hoang - Dem Ngay Xa Em.trc',NULL,0,'2021-06-09 19:21:43','2021-06-10 00:00:00'),(73,'eight','IU, SUGA','eight (Single)','eight-IU-SUGA_73','Korean',1,'IU, SUGA - eight.mp3','IU, SUGA - eight.jpg','/api/song/album?file=IU, SUGA - eight.jpg','IU, SUGA - eight.lrc',NULL,0,'2021-06-10 12:40:47','2021-06-10 00:00:00'),(75,'Có Chắc Yêu Là Đây','M-TP','Có Chắc Yêu Là Đây (Single)','Có-Chắc-Yêu-Là-Đây-M-TP_75','Vietnam',0,'Có Chắc Yêu Là Đây - Sơn Tùng M-TP (MP3 + lyric) _ TinMp3_128K.mp3','M-TP - Có Chắc Yêu Là Đây.jpg','/api/song/album?file=M-TP - Có Chắc Yêu Là Đây.jpg',NULL,NULL,0,'2021-06-10 12:40:53','2021-06-10 00:00:00'),(76,'Em Không Sai Chúng Ta Sai','ERIK','Em Không Sai Chúng Ta Sai (Single)','Em-Không-Sai-Chúng-Ta-Sai-ERIK_76','Vietnam',2,'EmKhongSaiChungTaSai_ERIK_-1079631096.mp3','ERIK - Em Không Sai Chúng Ta Sai.jpg','/api/song/album?file=ERIK - Em Không Sai Chúng Ta Sai.jpg',NULL,NULL,0,'2021-06-10 12:40:54','2021-06-10 00:00:00'),(77,'Gác Lại Âu Lo','Da LAB, Miu Lê','Gác Lại Âu Lo (Single)','Gác-Lại-Âu-Lo-Da-LAB-Miu-Lê_77','Vietnam',1,'GacLaiAuLo-DaLABMiuLe-6360815.mp3','Da LAB, Miu Lê - Gác Lại Âu Lo.jpg','/api/song/album?file=Da LAB, Miu Lê - Gác Lại Âu Lo.jpg',NULL,NULL,0,'2021-06-10 12:40:54','2021-06-10 00:00:00'),(78,'Hoa Hải Đường','Jack','Hoa Hải Đường (Single)','Hoa-Hải-Đường-Jack_78','Vietnam',0,'Hoa Hải Đường_Jack_-1082217477.mp3','Jack - Hoa Hải Đường.jpg','/api/song/album?file=Jack - Hoa Hải Đường.jpg','Jack - Hoa Hải Đường.lrc',NULL,0,'2021-06-10 12:40:54','2021-06-10 00:00:00'),(79,'De Mi Noi Cho Ma Nghe','Hoàng Thùy Linh','Hoang','De-Mi-Noi-Cho-Ma-Nghe-Hoàng-Thùy-Linh_79','Vietnam',4,'Hoang Thuy Linh - De Mi Noi Cho Ma Nghe.mp3','Hoàng Thùy Linh - De Mi Noi Cho Ma Nghe.jpg','/api/song/album?file=Hoàng Thùy Linh - De Mi Noi Cho Ma Nghe.jpg',NULL,NULL,0,'2021-06-10 12:40:54','2021-06-20 19:32:21'),(80,'Không Sao Mà, Em Đây Rồi','Suni Hạ Linh, Lou Hoàng','Không Sao Mà, Em Đây Rồi (Single)','Không-Sao-Mà-Em-Đây-Rồi-Suni-Hạ-Linh-Lou-Hoàng_80','Vietnam',0,'Không Sao Mà, Em Đây Rồi_Suni Hạ Linh, Lou Hoàng_-1079306404.mp3','Suni Hạ Linh, Lou Hoàng - Không Sao Mà, Em Đây Rồi.jpg','/api/song/album?file=Suni Hạ Linh, Lou Hoàng - Không Sao Mà, Em Đây Rồi.jpg','Suni Hạ Linh, Lou Hoàng - Không Sao Mà, Em Đây Rồi.trc',NULL,0,'2021-06-10 12:40:54','2021-06-21 16:32:21'),(81,'Nàng Thơ','Hoàng Dũng','Nàng Thơ (Single)','Nàng-Thơ-Hoàng-Dũng_81','Vietnam',0,'NangTho-HoangDung-6413381.mp3','Hoàng Dũng - Nàng Thơ.jpg','/api/song/album?file=Hoàng Dũng - Nàng Thơ.jpg',NULL,NULL,0,'2021-06-10 12:40:55','2021-06-22 10:32:21'),(83,'Yeu Em Dai Kho','Lou Hoàng','Yeu Em Dai Kho (Single)','Yeu-Em-Dai-Kho-Lou-Hoàng_83','Vietnam',2,'Yêu Em Dại Khờ_Lou Hoàng_-1078495477.mp3','Lou Hoàng - Yeu Em Dai Kho.jpg','/api/song/album?file=Lou Hoàng - Yeu Em Dai Kho.jpg',NULL,NULL,0,'2021-06-10 12:40:58','2021-06-24 12:32:21'),(84,'A Little Love','Fiona Fung','A Little Love','A-Little-Love-Fiona-Fung_84','US - UK',0,'Fiona Fung - A Little Love','Fiona Fung - A Little Love','/api/song/album?file=Fiona Fung - A Little Love.jpg','Fiona Fung - A Little Love.trc',NULL,0,'2021-06-25 17:35:41','2021-06-25 17:35:41'),(85,'All About That Bass','Meghan Trainor','Title - EP','All-About-That-Bass-Meghan-Trainor_85','US - UK',12,'Meghan Trainor - All About That Bass.mp3','Meghan Trainor - All About That Bass.jpg','/api/song/album?file=Meghan Trainor - All About That Bass.jpg','Meghan Trainor - All About That Bass.trc',NULL,0,'2021-06-25 17:54:38','2021-06-25 17:54:38'),(91,'A Better Day','JTL','NhacCuaTui.Com','A-Better-Day-JTL_91','Korean',0,'JTL - A Better Day.mp3',NULL,NULL,NULL,NULL,0,'2021-06-26 06:16:24','2021-06-27 07:55:05'),(93,'7 Years','Lukas Graham','Lukas Graham (Blue Album)','7-Years-Lukas-Graham_93','US - UK',0,'Lukas Graham - 7 Years.mp3',NULL,NULL,NULL,NULL,1,'2021-06-26 06:18:10','2021-06-30 18:44:49'),(94,'Mộng Uyên Ương Hồ Điệp','Dương Edward','Mộng Uyên Ương Hồ Điệp','Mộng-Uyên-Ương-Hồ-Điệp-Dương-Edward_94','Vietnam',2,'Dương Edward - Mộng Uyên Ương Hồ Điệp.mp3','Dương Edward - Mộng Uyên Ương Hồ Điệp.jpg','/api/song/album?file=Dương Edward - Mộng Uyên Ương Hồ Điệp.jpg','Dương Edward - Mộng Uyên Ương Hồ Điệp.trc',NULL,0,'2021-06-26 14:23:18','2021-06-26 14:23:18'),(95,'Breathles','Shayne Ward','Breathles - Nhac.vui.vn','Breathles-Shayne-Ward','US - UK',0,'Shayne Ward - Breathles.mp3','Shayne Ward - Breathles_1628181766.jpg','/api/song/picture?file=Shayne Ward - Breathles_1628181766.jpg','Shayne Ward - Breathles.trc',NULL,0,'2021-06-26 17:27:56','2021-12-03 00:36:47'),(96,'Bông Hoa Đẹp Nhất','Quân A.P','Bông Hoa Đẹp Nhất (Single)','Bông-Hoa-Đẹp-Nhất-Quân-A.P','Vietnam',5,'Quân A.P - Bông Hoa Đẹp Nhất.mp3','Quân A.P - Bông Hoa Đẹp Nhất_1624788847.jpg','/api/song/picture?file=Quân A.P - Bông Hoa Đẹp Nhất_1624788847.jpg','Quân A.P - Bông Hoa Đẹp Nhất.lrc',NULL,0,'2021-06-27 10:14:07','2021-12-03 00:37:01'),(97,'Chúng Ta Của Hiện Tại','Sơn Tùng M-TP','Chúng Ta Của Hiện Tại','Chúng-Ta-Của-Hiện-Tại-Sơn-Tùng-M-TP_97','Vietnam',14,'Sơn Tùng M-TP - Chúng Ta Của Hiện Tại.mp3','Sơn Tùng M-TP - Chúng Ta Của Hiện Tại_1624790221.jpg','/api/song/picture?file=Sơn Tùng M-TP - Chúng Ta Của Hiện Tại_1624790221.jpg',NULL,NULL,0,'2021-06-27 10:37:01','2021-06-27 10:37:01'),(98,'Because You Live','Jesse McCartney','mp3.zing.vn','Because-You-Live-Jesse-McCartney','US - UK',4,'Jesse McCartney - Because You Live.mp3','Jesse McCartney - Because You Live_1625507155.jpg','/api/song/picture?file=Jesse McCartney - Because You Live_1625507155.jpg','Jesse McCartney - Because You Live.trc',NULL,0,'2021-07-05 17:45:20','2021-12-03 00:36:54'),(99,'Trú Mưa - Này Cô Bé Ơi (Remix)','Umie x Teddy x $mile x meChill',NULL,'Trú-Mưa-Này-Cô-Bé-Ơi-(Remix)-Umie-x-Teddy-x-$mile-x-meChill','Vietnam',12,'Umie x Teddy x $mile x meChill - Trú Mưa - Này Cô Bé Ơi (Remix).mp3','Umie x Teddy x $mile x meChill - Trú Mưa - Này Cô Bé Ơi (Remix)_1637770465.jpg','/api/song/picture?file=Umie x Teddy x $mile x meChill - Trú Mưa - Này Cô Bé Ơi (Remix)_1637770465.jpg','Umie x Teddy x $mile x meChill - Trú Mưa - Này Cô Bé Ơi (Remix).trc',NULL,0,'2021-11-24 23:14:26','2021-12-05 23:44:20'),(100,'Anh Cu Di Di','Hari Won','mp3.zing.vn','Anh-Cu-Di-Di-Hari-Won','Vietnam',4,'Hari Won - Anh Cu Di Di.mp3','Hari Won - Anh Cu Di Di_1641199375.jpg','/api/song/picture?file=Hari Won - Anh Cu Di Di_1641199375.jpg','Hari Won - Anh Cu Di Di.trc',NULL,0,'2021-12-03 00:54:37','2022-01-03 15:42:55'),(102,'Anh Da Sai','OnlyC','mp3.zing.vn','Anh-Da-Sai-OnlyC','Vietnam',4,'OnlyC - Anh Da Sai.mp3','OnlyC - Anh Da Sai_1641199057.jpg','/api/song/picture?file=OnlyC - Anh Da Sai_1641199057.jpg',NULL,NULL,0,'2021-12-04 23:43:32','2022-01-03 15:37:37'),(103,'Apolozise','One Republic',NULL,'Apolozise-One-Republic','US - UK',0,'One Republic - Apolozise.mp3','One Republic - Apolozise_1641199107.jpg','/api/song/picture?file=One Republic - Apolozise_1641199107.jpg','Apolozise - OneRepublic.trc',NULL,0,'2022-01-03 15:38:28','2022-01-03 15:43:17'),(104,'As Long as You Love Me','Backstreet Boys','The Hits: Chapter One','As-Long-as-You-Love-Me-Backstreet-Boys','US - UK',0,'Backstreet Boys - As Long as You Love Me.mp3','Backstreet Boys - As Long as You Love Me_1641199458.jpg','/api/song/picture?file=Backstreet Boys - As Long as You Love Me_1641199458.jpg','Apolozise - OneRepublic.trc',NULL,0,'2022-01-03 15:43:41','2022-01-03 15:44:19');
/*!40000 ALTER TABLE `song` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','Admin','{bcrypt}$2a$10$4TENNUAwIX4uG8GX2UWPBOuVSB3mBIlMcSVzAePDI/DHieJKQ0P7O','2021-08-01 18:09:54','2021-08-01 18:09:54'),(2,'att','Tạ Anh Tú','{bcrypt}$2a$10$4TENNUAwIX4uG8GX2UWPBOuVSB3mBIlMcSVzAePDI/DHieJKQ0P7O','2021-08-01 18:09:54','2021-08-01 18:09:54'),(3,'tzk','Tuzaku','$2y$10$czUOi0363jrN7fSYjj96z.MgtIMGqmuc0IDORzt2q2vnISBq.piay','2021-08-01 11:10:45','2021-08-01 11:10:45'),(4,'demo','Demo','$2y$10$H82z0YGVuxDFxF1iOmCV7OrrKu6k7TqOE/.8ZJZt/IbR.vgE9qBOe','2021-08-01 11:35:31','2021-08-01 11:35:31');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (1,1,1),(2,1,2),(3,2,2);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-23  0:34:05