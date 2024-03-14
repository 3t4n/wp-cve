import React from 'react';
import { HashRouter, Route, Routes } from 'react-router-dom';
import ClickTracking from './ClickTracking';
import VisibilityTracking from './VisibilityTracking';
import VideoTracking from './VideoTracking';
import FormTracking from './FormTracking';
import EcommerceTracking from './WooTracking';

export default function CustomEvents() {
  return (
    <>
      <Routes>
        <Route index element={<ClickTracking />} />
        <Route path="click-tracking" element={<ClickTracking />} />
        <Route path="visibility-tracking" element={<VisibilityTracking />} />
        <Route path="ecommerce-tracking" element={<EcommerceTracking />} />
        <Route path="video-tracking" element={<VideoTracking />} />
        <Route path="form-tracking" element={<FormTracking />} />
      </Routes>
    </>
  );
}
