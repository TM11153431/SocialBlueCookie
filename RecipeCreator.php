<?php

/**
 *
 */
class RecipeCreator {

    private int $teaspoonsAvailable;
    private string $constraintTarget;
    private string $constraintType;
    private bool $removeConstraint;
    private int $amountIngredients;
    private int $hillClimbTries = 10;


    /**
     * @param $teaspoonsAvailable
     * @param $constraintTarget
     * @param $constraintType
     * @param $removeConstraint
     */
    public function __construct($teaspoonsAvailable, $constraintTarget, $constraintType, $removeConstraint)
    {
        $this->teaspoonsAvailable = $teaspoonsAvailable;
        $this->constraintTarget = $constraintTarget;
        $this->constraintType = $constraintType;
        $this->removeConstraint = $removeConstraint;
    }


    /**
     * Bruteforces the solution. Only works for 4 ingredients though
     * @param $ingredients
     * @return array|array[]
     */
    public function bruteforce($ingredients): array
    {
        $max = 0;
        $winningRecipe = [];
        // go over all permutations of:
        // [[0-{teaspoonsAvailable}], [0-{teaspoonsAvailable}], [0-{teaspoonsAvailable}], [0-{teaspoonsAvailable}]];
        for ($i = 0; $i <= $this->teaspoonsAvailable; $i++){
            for ($j = 0; $j <= $this->teaspoonsAvailable - $i; $j++){
                for ($k = 0; $k <= $this->teaspoonsAvailable - $i - $j; $k++){
                    $rest = $this->teaspoonsAvailable - $i - $j - $k;

                    // use values found that sum to teaspoonsAvailable, create dataset to calc score from
                    $valuesFound = [$i, $j, $k, $rest];
                    $ingredientMatrix = array();
                    // TODO: now this is generic, but the 4 for loops are not..
                    for ($m = 0; $m < count($ingredients); $m++){
                        $ingredientMatrix[] = [$ingredients[$m], $valuesFound[$m]];
                    }

                    // keep track of the max score found
                    // $max = max($this->calculateScore($this->transformData($dataset)), $max);

                    // keep track of max score found, and winning recipe
                    $transformedIngredientMatrix = $this->transformData($ingredientMatrix);
                    $checkedIngredientMatrix = $this->checkSumConstraint($transformedIngredientMatrix);
                    if (!$checkedIngredientMatrix["hit"]) continue;
                    $scoreFound = $this->calculateScore($checkedIngredientMatrix["ingredientMatrix"]);
                    if ($scoreFound > $max){
                        $max = $scoreFound;
                        $winningRecipe = $ingredientMatrix;
                    }
                }
            }
        }

        return [
            "max" => $max,
            "recipe" => $winningRecipe
        ];
    }

    /**
     * Quite hardcoded to only work for the current dataset.
     *
     * @param $dataset
     * @return array|array[]
     */
    private function transformData($dataset): array
    {
        $transformed = [
            "capacity" => [],
            "durability" => [],
            "flavor" => [],
            "texture" => [],
            "calories" => []
        ];

        foreach ($dataset as $datapoint){
            $transformed["capacity"][] = [$datapoint[0]->getCapacity(), $datapoint[1]];
            $transformed["durability"][] = [$datapoint[0]->getDurability(), $datapoint[1]];
            $transformed["flavor"][] = [$datapoint[0]->getFlavor(), $datapoint[1]];
            $transformed["texture"][] = [$datapoint[0]->getTexture(), $datapoint[1]];
            $transformed["calories"][] = [$datapoint[0]->getCalories(), $datapoint[1]];
        }

        return $transformed;
    }

    private function calculateScore($ingredientMatrix): int
    {
        // TODO: This way of determining which is first is suboptimal to say the least
        $totalScore = "first";

        // foreach parameter of the ingredient check total score, and add if higher than 0
        foreach ($ingredientMatrix as $ingredientCombination){
            $ingredientScore = 0;
            foreach ($ingredientCombination as $combination){
                $ingredientScore += $combination[0] * $combination[1];
            }
            $totalScore === 'first' ? $totalScore = max($ingredientScore, 0) : $totalScore *= max($ingredientScore, 0);
        }

        return $totalScore;
    }

    private function checkSumConstraint($ingredientMatrix): array
    {
        $constraintTotal = 0;
        foreach ($ingredientMatrix[$this->constraintType] as $constraintCombo){
            $constraintTotal += ($constraintCombo[0] * $constraintCombo[1]);
        }

        if ($this->removeConstraint){
            unset($ingredientMatrix[$this->constraintType]);
        }

        return [
            "hit" => $constraintTotal == $this->constraintTarget,
            "ingredientMatrix" => $ingredientMatrix
        ];
    }

    public function findOptimum($ingredients): void
    {
        // find optimum of ingredients with constraints: can only use teaspoonsAvailable in total
        $this->amountIngredients = count($ingredients);
        $startingValue = $this->teaspoonsAvailable / $this->amountIngredients;

        // TODO: This can probably be a oneliner
        $startingData = array();
        foreach ($ingredients as $ingredient){
            $startingData[] = [$ingredient, $startingValue];
        }

        $this->hillClimb($startingData);
    }

    private function hillClimb($dataset): void
    {
        $currentData = $dataset;
        $currentOptimum = $this->calculateScore($this->transformData($currentData));

        $tries = 0;

        while ($tries < $this->hillClimbTries){
            $tries++;

            $newData = $this->shuffleDataset($currentData);
            $newBest = $this->calculateScore($this->transformData($newData));

            if ($newBest > $currentOptimum) {
                $currentOptimum = $newBest;
                $currentData = $newData;
                $tries = 0;
                echo "Most recent found best score: " . $currentOptimum . PHP_EOL;
            }
        }

    }

    private function shuffleDataset($dataset): array
    {
        $teaspoonsInReserve = 0;

        for ($i = 0; $i < $this->amountIngredients; $i++){
            if ($i == $this->amountIngredients - 1) {
                $dataset[$i][1] += $teaspoonsInReserve;
                break;
            }
            $randomChange = rand(-1, 1);
            $teaspoonsInReserve -= $randomChange;
            $dataset[$i][1] += $randomChange;
        }

        return $dataset;
    }
}

