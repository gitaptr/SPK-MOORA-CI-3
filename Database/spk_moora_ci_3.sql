-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 01 Sep 2022 pada 03.43
-- Versi Server: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_moora_ci_3`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatif`
--

CREATE TABLE IF NOT EXISTS `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama`) VALUES
(40, 'Sumarlin Sinaga'),
(41, 'Tiolom Silalahi '),
(42, 'Suarmi'),
(43, 'Hitler Saragi'),
(44, 'Rotua Silalahi '),
(45, 'Susanti'),
(46, 'Yulianti'),
(47, 'Satini'),
(48, 'Paimun'),
(49, 'Saminah');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_moora`
--

CREATE TABLE IF NOT EXISTS `hasil_moora` (
  `id_hasil_moora` int(11) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `hasil_moora`
--

INSERT INTO `hasil_moora` (`id_hasil_moora`, `id_alternatif`, `nilai`) VALUES
(1, 40, 2.1847),
(2, 41, 2.3562),
(3, 42, 2.9039),
(4, 43, 1.8116),
(5, 44, 2.3562),
(6, 45, 2.0969),
(7, 46, 3.0665),
(8, 47, 1.7366),
(9, 48, 2.9895),
(10, 49, 2.5331);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE IF NOT EXISTS `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `kode_kriteria` varchar(100) NOT NULL,
  `bobot` float NOT NULL,
  `jenis` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `keterangan`, `kode_kriteria`, `bobot`, `jenis`) VALUES
(38, 'Pekerjaan', 'C1', 1, 'Benefit'),
(39, 'Penghasilan', 'C2', 1, 'Cost'),
(40, 'Lantai', 'C3', 2, 'Benefit'),
(41, ' Jenis Dinding', 'C4', 2, 'Benefit'),
(42, 'MCK', 'C5', 2, 'Benefit'),
(43, 'Jenis Atap', 'C6', 2, 'Benefit');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian`
--

CREATE TABLE IF NOT EXISTS `penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nilai` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penilaian`
--

INSERT INTO `penilaian` (`id_penilaian`, `id_alternatif`, `id_kriteria`, `nilai`) VALUES
(74, 40, 38, 53),
(75, 40, 39, 57),
(76, 40, 40, 61),
(77, 40, 41, 63),
(78, 40, 42, 68),
(79, 40, 43, 69),
(80, 41, 38, 53),
(81, 41, 39, 57),
(82, 41, 40, 60),
(83, 41, 41, 63),
(84, 41, 42, 68),
(85, 41, 43, 69),
(86, 42, 38, 53),
(87, 42, 39, 57),
(88, 42, 40, 61),
(89, 42, 41, 62),
(90, 42, 42, 67),
(91, 42, 43, 69),
(92, 43, 38, 54),
(93, 43, 39, 58),
(94, 43, 40, 61),
(95, 43, 41, 65),
(96, 43, 42, 68),
(97, 43, 43, 69),
(98, 44, 38, 53),
(99, 44, 39, 57),
(100, 44, 40, 60),
(101, 44, 41, 63),
(102, 44, 42, 68),
(103, 44, 43, 69),
(104, 45, 38, 55),
(105, 45, 39, 56),
(106, 45, 40, 61),
(107, 45, 41, 65),
(108, 45, 42, 67),
(109, 45, 43, 69),
(110, 46, 38, 53),
(111, 46, 39, 57),
(112, 46, 40, 59),
(113, 46, 41, 63),
(114, 46, 42, 67),
(115, 46, 43, 69),
(116, 47, 38, 52),
(117, 47, 39, 57),
(118, 47, 40, 61),
(119, 47, 41, 66),
(120, 47, 42, 68),
(121, 47, 43, 69),
(122, 48, 38, 51),
(123, 48, 39, 58),
(124, 48, 40, 61),
(125, 48, 41, 63),
(126, 48, 42, 67),
(127, 48, 43, 69),
(128, 49, 38, 51),
(129, 49, 39, 57),
(130, 49, 40, 59),
(131, 49, 41, 64),
(132, 49, 42, 68),
(133, 49, 43, 69);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_kriteria`
--

CREATE TABLE IF NOT EXISTS `sub_kriteria` (
  `id_sub_kriteria` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `deskripsi` varchar(200) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id_sub_kriteria`, `id_kriteria`, `deskripsi`, `nilai`) VALUES
(51, 38, 'Pensiunan', 5),
(52, 38, 'Ibu Rumah Tangga', 4),
(53, 38, 'Petani / Pekebun', 3),
(54, 38, 'Wiraswasta', 2),
(55, 38, 'Karyawan BUMN', 1),
(56, 39, '1.000.000 - 1.100.000', 5),
(57, 39, '1.200.000 - 1.300.000', 4),
(58, 39, '1.400.000 - 1.500.000', 3),
(59, 40, 'Tanah', 5),
(60, 40, 'Papan', 4),
(61, 40, 'Rabat Beton', 3),
(62, 41, 'Gedek', 5),
(63, 41, 'Papan', 4),
(64, 41, 'Papan + Gedek', 3),
(65, 41, 'Papan + Bata', 2),
(66, 41, 'Papan + Bata + Gedek', 1),
(67, 42, 'Tidak', 5),
(68, 42, 'Ya', 2),
(69, 43, 'Seng Gelombang', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL,
  `id_user_level` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `id_user_level`, `nama`, `email`, `username`, `password`) VALUES
(1, 1, 'Admin', 'admin@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3'),
(7, 2, 'User', 'user@gmail.com', 'user', 'ee11cbb19052e40b07aac0ca060c23ee');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_level`
--

CREATE TABLE IF NOT EXISTS `user_level` (
  `id_user_level` int(11) NOT NULL,
  `user_level` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_level`
--

INSERT INTO `user_level` (`id_user_level`, `user_level`) VALUES
(1, 'Administrator'),
(2, 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `hasil_moora`
--
ALTER TABLE `hasil_moora`
  ADD PRIMARY KEY (`id_hasil_moora`),
  ADD KEY `id_alternatif` (`id_alternatif`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `id_alternatif` (`id_alternatif`),
  ADD KEY `id_kriteria` (`id_kriteria`),
  ADD KEY `nilai` (`nilai`);

--
-- Indexes for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id_sub_kriteria`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_user_level` (`id_user_level`);

--
-- Indexes for table `user_level`
--
ALTER TABLE `user_level`
  ADD PRIMARY KEY (`id_user_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `hasil_moora`
--
ALTER TABLE `hasil_moora`
  MODIFY `id_hasil_moora` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=134;
--
-- AUTO_INCREMENT for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  MODIFY `id_sub_kriteria` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user_level`
--
ALTER TABLE `user_level`
  MODIFY `id_user_level` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `hasil_moora`
--
ALTER TABLE `hasil_moora`
  ADD CONSTRAINT `hasil_moora_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `penilaian_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_3` FOREIGN KEY (`nilai`) REFERENCES `sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD CONSTRAINT `sub_kriteria_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_user_level`) REFERENCES `user_level` (`id_user_level`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
