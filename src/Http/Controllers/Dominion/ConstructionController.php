<?php

namespace OpenDominion\Http\Controllers\Dominion;

use Exception;
use OpenDominion\Calculators\Dominion\Actions\ConstructionCalculator;
use OpenDominion\Calculators\Dominion\BuildingCalculator;
use OpenDominion\Calculators\Dominion\LandCalculator;
use OpenDominion\Helpers\BuildingHelper;
use OpenDominion\Http\Requests\Dominion\Actions\ConstructActionRequest;
use OpenDominion\Http\Requests\Dominion\Actions\DestroyActionRequest;
use OpenDominion\Services\Analytics\AnalyticsEvent;
use OpenDominion\Services\Analytics\AnalyticsService;
use OpenDominion\Services\Dominion\Actions\ConstructActionService;
use OpenDominion\Services\Dominion\Actions\DestroyActionService;
use OpenDominion\Services\Dominion\Queue\ConstructionQueueService;

class ConstructionController extends AbstractDominionController
{
    public function getConstruction()
    {
        return view('pages.dominion.construction', [
            'buildingCalculator' => app(BuildingCalculator::class),
            'buildingHelper' => app(BuildingHelper::class),
            'constructionCalculator' => app(ConstructionCalculator::class),
            'constructionQueueService' => app(ConstructionQueueService::class),
            'landCalculator' => app(LandCalculator::class),
        ]);
    }

    public function postConstruction(ConstructActionRequest $request)
    {
        $dominion = $this->getSelectedDominion();
        $constructionActionService = app(ConstructActionService::class);

        try {
            $result = $constructionActionService->construct($dominion, $request->get('construct'));

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors([$e->getMessage()]);
        }

        $message = sprintf(
            'Construction started at a cost of %s platinum and %s lumber.',
            number_format($result['platinumCost']),
            number_format($result['lumberCost'])
        );

        // todo: fire laravel event
        $analyticsService = app(AnalyticsService::class);
        $analyticsService->queueFlashEvent(new AnalyticsEvent(
            'dominion',
            'construct',
            '',
            array_sum($request->get('construct')) // todo: get from $result
        ));

        $request->session()->flash('alert-success', $message);
        return redirect()->route('dominion.construction');
    }

    public function getDestroy()
    {
        return view('pages.dominion.destroy', [
            'buildingCalculator' => app(BuildingCalculator::class),
            'buildingHelper' => app(BuildingHelper::class),
            'landCalculator' => app(LandCalculator::class),
        ]);
    }

    public function postDestroy(DestroyActionRequest $request)
    {
        $dominion = $this->getSelectedDominion();
        $destroyActionService = app(DestroyActionService::class);

        try {
            $result = $destroyActionService->destroy($dominion, $request->get('destroy'));

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors([$e->getMessage()]);
        }

        $message = sprintf(
            'Destruction of %s buildings is complete.',
            number_format($result['totalBuildingsDestroyed'])
        );

        // todo: laravel event
        $analyticsService = app(AnalyticsService::class);
        $analyticsService->queueFlashEvent(new AnalyticsEvent(
            'dominion',
            'destroy',
            '',
            $result['totalBuildingsDestroyed']
        ));

        $request->session()->flash('alert-success', $message);
        return redirect()->route('dominion.destroy');
    }
}
