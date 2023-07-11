CREATE TABLE `veiculos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` varchar(155) NOT NULL,
  `veiculo` varchar(255) NOT NULL,
  `ano` varchar(4) NOT NULL,
  `descricao` text NOT NULL,
  `vendido` int(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4;

INSERT INTO `veiculos` (`marca`, `veiculo`, `ano`, `descricao`, `vendido`, `created`)
VALUES
  ('Ford', 'Mustang', '2022', 'Um carro esportivo elegante com excelente desempenho.', 0, NOW()),
  ('Toyota', 'Corolla', '2021', 'Um sedan confiável e econômico.', 1, NOW()),
  ('Chevrolet', 'Camaro', '2020', 'Um carro esportivo com um design impressionante.', 0, NOW()),
  ('Honda', 'Civic', '2021', 'Um sedan confortável e eficiente em termos de combustível.', 0, NOW()),
  ('BMW', 'X5', '2019', 'Um SUV de luxo com tecnologia avançada.', 1, NOW());
