'use strict';

const { __, _x, _n, _nx, sprintf } = wp.i18n;

window.LaStudioKitDashboardEventBus = new Vue();

window.LaStudioKitDashboard = new LaStudioKitDashboardClass();

window.LaStudioKitDashboard.initVueComponents();

window.LaStudioKitDashboardPageInstance = LaStudioKitDashboard.initDashboardPageInstance();