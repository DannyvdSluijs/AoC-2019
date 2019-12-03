<?php declare(strict_types=1);

namespace AoC\General\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Puzzle1 extends Command
{
    protected static $defaultName = 'puzzle-1';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $content = $this->getContent();
        $lines = explode("\n", $content);

        $fuel = 0;
        foreach ($lines as $weight) {
            $moduleFuel = $this->calculateModuleFuelRequirement((int) $weight);
            $additionalFuel = $this->calculateFuelForTheFuel($moduleFuel);
            $moduleTotal = $moduleFuel + $additionalFuel;
            $output->writeln("For module with weight $weight we need $moduleTotal");
            $fuel += $moduleTotal;
        }

        $output->writeln("In total we need $fuel");
        return 1;
    }

    private function calculateModuleFuelRequirement(int $weight): int
    {
        $divided = $weight / 3;
        $floored = floor($divided);
        return max(0, (int) $floored - 2);
    }

    public function calculateFuelForTheFuel(int $fuel): int
    {
        $f = $this->calculateModuleFuelRequirement($fuel);

        if ($f === 0) {
            return 0;
        }

        return $f + $this->calculateFuelForTheFuel($f);
    }

    private function getContent(): string
    {
        return '131787
116597
71331
101986
56538
105039
119405
87762
113957
69613
63698
117674
72876
105026
83620
132592
137403
96832
58387
97609
50978
52896
145584
140832
74504
52998
64722
143334
89601
89326
85906
117840
91299
50593
74470
141591
61069
130479
69195
77411
106137
80954
117644
113063
127587
148770
71286
123430
133562
121053
64311
52818
148583
107511
92838
79724
122022
122602
50344
56938
102363
123140
105469
72773
96023
53669
70394
100930
55213
53756
62225
57172
56049
64661
112321
59872
111597
115958
105468
62111
72865
80323
103897
137687
70178
113314
122121
128654
136723
77279
104806
103491
92168
119263
128791
102237
86578
92728
104785
116658';
    }
}
