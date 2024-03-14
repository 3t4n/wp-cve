<?php
class CwmpParcelamentoFixo {
    private $valorTotal;
    private $taxaFixa;
    private $parcelasSemJuros;
    private $valorMinimoParcela;
    private $numeroMaxParcelas;
    public function __construct($valorTotal, $taxaFixa, $parcelasSemJuros, $valorMinimoParcela, $numeroMaxParcelas) {
        $this->valorTotal = $valorTotal;
        $this->taxaFixa = $taxaFixa;
        $this->parcelasSemJuros = $parcelasSemJuros;
        $this->valorMinimoParcela = $valorMinimoParcela;
        $this->numeroMaxParcelas = $numeroMaxParcelas;
    }
    public function calcularTotalComJuros($numeroParcelas) {
        $valorTotalComJuros = $this->valorTotal * pow((1 + $this->taxaFixa), $numeroParcelas);
        return $valorTotalComJuros;
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
		if ($this->parcelasSemJuros <= 0) {
			return [];
		}

		$valorPorParcelaSemJuros = floatval($this->valorTotal) / intval($this->parcelasSemJuros);
		$parcelas = [];
		for ($i = 1; $i <= intval($this->parcelasSemJuros); $i++) {
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
            $valorMinimoPorParcelaSemJuros = $this->valorMinimoParcela / $this->parcelasSemJuros;
            $numeroParcelas = ceil($this->valorTotal / $valorMinimoPorParcelaSemJuros);
            $valorParcela = ceil($this->valorTotal / $numeroParcelas);
            
            $parcelas[] = [
                'numero' => $numeroParcelas,
                'valor' => $valorParcela,
                'juros' => 0
            ];
        }
        for ($i = $this->parcelasSemJuros + 1; $i <= $this->numeroMaxParcelas; $i++) {
            $valorPorParcela = ($this->valorTotal * $this->taxaFixa) / (1 - pow(1 + $this->taxaFixa, -$i));
            if ($valorPorParcela >= $this->valorMinimoParcela) {
                $parcelas[] = [
                    'numero' => $i,
                    'valor' => $valorPorParcela,
                    'juros' => $valorPorParcela - ($this->valorTotal / $i)
                ];
            }
        }

        return $parcelas;
    }
    public function calcularNumeroParcelasPossiveis($exibirParcelasSemJuros = true) {
        if ($this->valorTotal < $this->valorMinimoParcela) {
            return [
                'numero' => 1,
                'valor' => $this->valorTotal
            ];
        }
        $parcelasSemJurosPossiveis = [];
        for ($i = 1; $i <= $this->parcelasSemJuros; $i++) {
            $valorParcelaSemJuros = $this->valorTotal / $i;
            if ($valorParcelaSemJuros >= $this->valorMinimoParcela) {
                $parcelasSemJurosPossiveis[] = [
                    'numero' => $i,
                    'valor' => $valorParcelaSemJuros
                ];
            }
        }
        if ($exibirParcelasSemJuros) {
            if (!empty($parcelasSemJurosPossiveis)) {
                $ultimaParcela = end($parcelasSemJurosPossiveis);
                return $ultimaParcela;
            } else {
                return false;
            }
        } else {
            $parcelasComJurosPossiveis = [];
			for ($i = intval($this->parcelasSemJuros) + 1; $i <= intval($this->numeroMaxParcelas); $i++) {
				$valorPorParcela = ($this->valorTotal * $this->taxaFixa) / (1 - pow(1 + $this->taxaFixa, -$i));
				if ($valorPorParcela >= floatval($this->valorMinimoParcela)) {
					$parcelasComJurosPossiveis[] = [
						'numero' => $i,
						'valor' => $valorPorParcela
					];
				}
			}
            if (!empty($parcelasComJurosPossiveis)) {
                $ultimaParcela = end($parcelasComJurosPossiveis);
                return $ultimaParcela;
            } else {
                return false;
            }
        }
    }
}

