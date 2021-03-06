<?php declare(strict_types=1);

namespace AoC\General\Command;

use AoC\General\Model\RealIntCodeComputer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle14Part1 extends Command
{
    protected static $defaultName = 'puzzle-14-part-1';

    private $recipies = [];
    private $stock = ['ORE' => PHP_INT_MAX, 'FUEL' => 0];
    /** @var OutputInterface */
    private $output;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        foreach (explode("\n", $this->getPuzzleInput()) as $line) {
            list ($in, $out) = explode('=>', $line);
            sscanf(trim($out), '%d %s', $outAmount, $outType);
            $outObject = (object) ['type' => $outType, 'amount' => $outAmount];
            $this->recipies[$outType] = (object) ['output' => $outObject, 'input' => []];
            foreach (explode(',', $in) as $inElement) {
                sscanf(trim($inElement), '%d %s', $inAmount, $inType);
                $inObject = (object) ['type' => $inType, 'amount' => $inAmount];
                $this->recipies[$outType]->input[] = $inObject;
            }
        }

        $this->take('FUEL', 1);

        $used = PHP_INT_MAX - $this->stock['ORE'];
        $output->writeln("$used ore was required to produce 1 FUEL");
        return 1;
    }

    private function take(string $type, int $amount): void
    {
        $this->output->writeln("Taking $amount $type");
        if (! array_key_exists($type, $this->stock)) {
            $this->stock[$type] = 0;
        }
        if ($this->stock[$type] < $amount) {
            $this->produce($type, $amount - $this->stock[$type]);
        }

        $this->stock[$type] -= $amount;
        $this->output->writeln("Stock level for $type decreased to {$this->stock[$type]} (-$amount)");
    }

    private function produce(string $type, int $amount)
    {
        $recipie = $this->recipies[$type];
        $this->output->writeln("Producing $amount $type");

        $productionRuns = (int) ceil($amount / $recipie->output->amount);

        foreach ($recipie->input as $input) {
            $this->take($input->type, $input->amount * $productionRuns);
        }

        $productionAmount = $productionRuns * $recipie->output->amount;
        $this->stock[$type] += $productionAmount;
        $this->output->writeln("Stock level for $type increased to {$this->stock[$type]} (+{$productionAmount})");
    }

    private function getPuzzleInput(): string
    {
        return '10 KVPH => 5 HPRK
5 RSTBJ => 5 QKBQL
2 GZWFN, 21 WBPFQ => 5 KMFWH
5 JDJB, 1 FSWFT, 1 NKVSV => 6 MGKSL
5 BCRHK => 9 KXFTL
23 NKVSV, 2 RSTBJ => 9 QPBVD
19 BKFVS, 7 JZBFT => 7 XWTQ
14 JLXP, 4 LSCL => 8 FWLTD
173 ORE => 5 TZSDV
2 FPVH, 1 JDJB, 3 KHRW => 2 QLNJ
1 HTGMX, 1 GVJVK, 2 RLRK => 2 HWBM
1 GLVHT, 1 PBCT, 5 ZWKGV, 1 QSVJ, 2 FWLTD, 3 CNVPB, 1 QGNL => 8 RNLTX
1 KXZTS => 2 BKFVS
1 KVPH, 6 PVHPV, 2 TZSDV => 4 RLRK
118 ORE => 1 VRVZ
7 MGKSL, 4 HWBM => 2 GZWFN
5 PVHPV => 7 HTGMX
25 LSCL, 12 GVMFW => 6 ZWKGV
1 CTPND, 1 KXZTS => 3 FRQH
1 KXFTL => 3 PBCT
1 CMPX => 4 KZNBL
2 HDQVB, 1 QPBVD => 5 CTPND
14 KVPH => 1 FCBQN
3 XWTQ, 22 CTHM, 4 KVPH, 4 BZTV, 1 KMFWH, 12 NRFK => 7 CXVR
1 GVJVK => 7 RSTBJ
1 GVJVK => 4 NSQHW
3 NKVSV => 8 KHRW
8 HDQVB, 9 BCRHK => 6 GVMFW
142 ORE => 7 KVPH
4 TZSDV => 2 GVJVK
4 KVPH, 10 HWBM => 3 NRFK
47 PBCT, 15 CXVR, 45 GVJVK, 23 KZNBL, 1 WFPNP, 14 RNLTX => 1 FUEL
1 PCBNG => 4 QLJXM
1 SHTQF => 2 FNWBZ
2 FCBQN, 1 BCRHK => 5 HVFBV
1 BZTQ => 9 CTHM
16 SHTQF => 3 BZTQ
11 PBCT, 5 PCBNG, 2 CTPND => 1 WBPFQ
3 KHRW => 4 FSWFT
12 HDQVB, 1 PBCT, 9 NRFK => 9 VLWJL
5 SHTQF, 8 HVFBV => 6 BZTV
2 KZNBL, 7 NRFK => 3 DVFS
18 HTLSF, 14 DVFS => 6 TLFNL
1 RSTBJ => 1 NKVSV
2 QLNJ, 7 BZTQ => 6 PCBNG
1 HTLSF, 19 CMPX => 7 JDJB
6 KZNBL, 3 QSVJ => 8 SHTQF
3 HTLSF, 1 VRVZ => 6 CMPX
1 MGKSL, 15 CTPND => 6 STNPH
2 NKVSV, 7 JDJB => 4 KXZTS
3 KVPH => 4 QSVJ
1 HPRK, 9 PCBNG, 2 KXFTL => 9 CNVPB
27 GZWFN, 1 VLWJL, 15 LSCL => 3 GLVHT
162 ORE => 4 HTLSF
193 ORE => 8 PVHPV
9 TLFNL, 1 KHRW => 6 HDQVB
6 QLJXM, 4 FCBQN => 7 JLXP
3 HTLSF, 21 NSQHW, 18 GVJVK => 7 BCRHK
1 HTGMX, 20 CMPX, 6 RSTBJ => 6 FPVH
4 KXZTS, 7 CNVPB, 1 STNPH => 2 LSCL
3 KXZTS, 1 PCBNG => 3 JZBFT
22 WBPFQ, 22 FRQH, 1 QLNJ, 4 CTHM, 3 GVMFW, 1 KMFWH, 4 QKBQL => 4 WFPNP
3 QLJXM, 11 FNWBZ, 3 WBPFQ => 5 QGNL';
    }
}
