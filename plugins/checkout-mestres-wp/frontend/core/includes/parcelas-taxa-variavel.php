<?php
class CwmpParcelamentoVariavel {
    private $valorTotal;
    private $taxasPorMes;
    private $parcelasSemJuros;
    private $valorMinimoParcela;
    private $numeroMaxParcelas;

    public function __construct($valorTotal, $taxasPorMes, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas) {
        $this->valorTotal = $valorTotal;
        $this->taxasPorMes = $taxasPorMes;
        $this->parcelasSemJuros = $parcelasSemJuros;
        $this->valorMinimoParcela = $valorMinimoParcela;
        $this->numeroMaxParcelas = $numeroMaxParcelas;
    }

    public function calcularParcelas() {
        if ($this->valorTotal < $this->valorMinimoParcela) {
            return [
                [
                    'numero' => 1,
                    'valor' => $this->valorTotal,
                    'juros' => 0
                ]
            ];
        }

        $parcelas = [];
        $taxaIndex = 0;

        for ($i = 1; $i <= $this->parcelasSemJuros; $i++) {
            $valorParcela = $this->valorTotal / $i;
            if ($valorParcela >= $this->valorMinimoParcela) {
                $parcelas[] = [
                    'numero' => $i,
                    'valor' => $valorParcela,
                    'juros' => 0
                ];
            }
        }

		if (empty($parcelas)) {
			if (intval($this->parcelasSemJuros) > 0) {
				$valorMinimoPorParcelaSemJuros = intval($this->valorMinimoParcela) / intval($this->parcelasSemJuros);
				$numeroParcelas = ceil($this->valorTotal / $valorMinimoPorParcelaSemJuros);
				$valorParcela = ceil($this->valorTotal / $numeroParcelas);
				
				$parcelas[] = [
					'numero' => $numeroParcelas,
					'valor' => $valorParcela,
					'juros' => 0
				];
			} else {
				// Tratar o caso em que $this->parcelasSemJuros é igual a zero
				// Por exemplo, exibir uma mensagem de erro ou tomar outra ação apropriada
			}
		}


		for ($i = intval($this->parcelasSemJuros) + 1; $i <= intval($this->numeroMaxParcelas); $i++) {
			$taxa = $this->taxasPorMes[$taxaIndex];
			$valorPorParcela = ($this->valorTotal * $taxa) / (1 - pow(1 + $taxa, -$i));

			if ($valorPorParcela >= $this->valorMinimoParcela) {
				$parcelas[] = [
					'numero' => $i,
					'valor' => $valorPorParcela,
					'juros' => $valorPorParcela - ($this->valorTotal / $i)
				];
			}

			$taxaIndex++;
			if ($taxaIndex >= count($this->taxasPorMes)) {
				$taxaIndex = 0;
			}
		}


        return $parcelas;
    }

	public function calcularNumeroParcelasPossiveis() {
		$parcelasSemJurosPossiveis = [];
		$parcelasComJurosPossiveis = [];

		if ($this->valorTotal < $this->valorMinimoParcela) {
			return [
				'sem_juros' => [
					'numero' => 1,
					'valor' => $this->valorTotal
				],
				'com_juros' => [
					'numero' => 1,
					'valor' => $this->valorTotal
				]
			];
		}

		for ($i = 1; $i <= $this->parcelasSemJuros; $i++) {
			$valorParcelaSemJuros = $this->valorTotal / $i;
			if ($valorParcelaSemJuros >= $this->valorMinimoParcela) {
				$parcelasSemJurosPossiveis[] = [
					'numero' => $i,
					'valor' => $valorParcelaSemJuros
				];
			}
		}

		if (is_array($parcelasSemJurosPossiveis) && !empty($parcelasSemJurosPossiveis)) {
			$ultimaParcelaSemJuros = end($parcelasSemJurosPossiveis);
		} else {
			$ultimaParcelaSemJuros = "Não é possível parcelar sem juros.";
		}


		$taxaIndex = 0;
		for ($i = intval($this->parcelasSemJuros) + 1; $i <= intval($this->numeroMaxParcelas); $i++) {
			$taxa = $this->taxasPorMes[$taxaIndex];
			$valorPorParcela = ($this->valorTotal * $taxa) / (1 - pow(1 + $taxa, -$i));

			if ($valorPorParcela >= $this->valorMinimoParcela) {
				$parcelasComJurosPossiveis[] = [
					'numero' => $i,
					'valor' => $valorPorParcela
				];
			}

			$taxaIndex++;
			if ($taxaIndex >= count($this->taxasPorMes)) {
				$taxaIndex = 0;
			}
		}
		if (!empty($parcelasComJurosPossiveis)) {
			$ultimaParcelaComJuros = end($parcelasComJurosPossiveis);
		} else {
			$ultimaParcelaComJuros = "Não é possível parcelar com juros.";
		}

		return [
			'sem_juros' => $ultimaParcelaSemJuros,
			'com_juros' => $ultimaParcelaComJuros
		];
	}
}

