<?php

include('Ingredient.php');
include('RecipeCreator.php');

/**
 * @return array
 */
function init_data():array
{
    // TODO: actually parse and use inputFile
    $sprinkles = new Ingredient("sprinkles", 2, 0, -2, 0, 3);
    $butterscotch = new Ingredient("butterscotch", 0, 5, -3, 0, 3);
    $chocolate = new Ingredient("chocolate", 0, 0, 5, -1, 8);
    $candy = new Ingredient("candy", 0, -1, 0, 5, 8);

    return [$sprinkles, $butterscotch, $chocolate, $candy];
}

function main(): void
{
    global $argc, $argv;

    if ($argc != 5)
    {
        echo "usage: php main.php {teaspoons} {constraintTarget} {constraintType} {removeConstraint} \n";
        return;
    }

    $ingredients = init_data();

    $recipeCreator = new RecipeCreator($argv[1], intval($argv[2]), $argv[3], $argv[4]);
    $winner = $recipeCreator->bruteforce($ingredients);

    echo "--------- RESULTS ---------" . PHP_EOL;
    echo "Winning score: {$winner["max"]}" . PHP_EOL;
    echo "Winning recipe:" . PHP_EOL;
    foreach ($winner["recipe"] as $ingredientMatrix)
    {
        echo "$ingredientMatrix[1] teaspoons of {$ingredientMatrix[0]->getType()}" . PHP_EOL;
    }
}

main();



