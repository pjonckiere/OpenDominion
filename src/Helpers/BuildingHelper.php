<?php

namespace OpenDominion\Helpers;

use OpenDominion\Models\Race;

class BuildingHelper
{
    public function getBuildingTypes()
    {
        return [
            'home',
            'alchemy',
            'farm',
            'smithy',
            'masonry',
            'ore_mine',
            'gryphon_nest',
            'tower',
            'wizard_guild',
            'temple',
            'diamond_mine',
            'school',
            'lumberyard',
            'forest_haven',
            'factory',
            'guard_tower',
            'shrine',
            'barracks',
            'dock',
        ];
    }

    public function getBuildingTypesByRace(Race $race = null)
    {
        $return = [
            'plain' => [
                'alchemy',
                'farm',
                'smithy',
                'masonry',
            ],
            'mountain' => [
                'ore_mine',
                'gryphon_nest',
            ],
            'swamp' => [
                'tower',
                'wizard_guild',
                'temple',
            ],
            'cavern' => [
                'diamond_mine',
                'school',
            ],
            'forest' => [
                'lumberyard',
                'forest_haven',
            ],
            'hill' => [
                'factory',
                'guard_tower',
                'shrine',
                'barracks',
            ],
            'water' => [
                'dock',
            ],
        ];

        if ($race !== null) {
            array_unshift($return[$race->home_land_type], 'home');
        }

        return $return;
    }

    // temp
    public function getBuildingImplementedString($buildingType)
    {
        // 0 = nyi
        // 1 = partial implemented
        // 2 = implemented

        $buildingTypes = [
            'home' => 2,
            'alchemy' => 2,
            'farm' => 2,
            'smithy' => 0, // reduce military unit cost
            'masonry' => 0, // increase castle bonuses
            'ore_mine' => 2,
            'gryphon_nest' => 2,
            'tower' => 2,
            'wizard_guild' => 0, // increase wizard strength
            'temple' => 0, // increase population growth, reduce defensive bonuses of target dominion during invasion
            'diamond_mine' => 2,
            'school' => 0, // produces research points
            'lumberyard' => 2,
            'forest_haven' => 1, // reduce losses on failed spy ops, reduce fireball damage, reduce plat stolemn
            'factory' => 0, // reduce construction costs and rezoning costs
            'guard_tower' => 2,
            'shrine' => 0, // reduce casualties on offense, increases chance of hero level gain?, increase hero bonuses?
            'barracks' => 2,
            'dock' => 1, // produces boats, prevents boats being sunk
        ];

        switch ($buildingTypes[$buildingType]) {
            case 0:
                return '<abbr title="Not yet implemented" class="label label-danger">NYI</abbr>';
                break;

            case 1:
                return '<abbr title="Partially implemented" class="label label-warning">PI</abbr>';
                break;

//            case 2:
//                break;
        }

        return null;
    }

    public function getBuildingHelpString($buildingType)
    {
        $helpStrings = [
            'home' => 'Houses 30 people',
            'alchemy' => 'Produces 45 platinum',
            'farm' => 'Produces 80 food',
            'smithy' => 'Reduces non-wizard/archmage/spy unit costs (up to 18% of land)',
            'masonry' => 'Increases castle bonuses. Also reduces lightning bolt damage (up to 33.3% of land)',
            'ore_mine' => 'Produces 60 ore',
            'gryphon_nest' => 'Increases offensive power (up to 20% of land)',
            'tower' => 'Produces 25 mana',
            'wizard_guild' => 'Increases wizard strength refresh rate, decreases wizard/archmage cost and decreases spell costs (up to 20% of land)',
            'temple' => 'Increases population growth, reduces defensive bonuses of dominions you invade',
            'diamond_mine' => 'Produces 15 gems',
            'school' => 'Produces research points',
            'lumberyard' => 'Produces 50 lumber',
            'forest_haven' => 'Reduces losses on failed spy ops, reduces incoming fireball damage and reduces the amount of platinum being stolen from you (up to 10% of land). Also increases peasant defense',
            'factory' => 'Reduces construction costs (up to 18.75% of land) and rezoning costs (up to 25% of land)',
            'guard_tower' => 'Increases defensive power (up to 20% of land)',
            'shrine' => 'Reduces casualties on offense (up to 20% of land)',
            'barracks' => 'Houses 36 military units',
            'dock' => 'Produces 1 boat every 20 hours on average, produces 35 food and prevents 2.5 your boats from being sunk',
        ];

        return $helpStrings[$buildingType] ?: null;
    }
}
