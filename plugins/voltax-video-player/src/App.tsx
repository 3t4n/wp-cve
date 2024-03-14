import React from 'react'

import { MediaProvider } from './library/utils/context/MediaContext'
import VideoLayout from './layouts/VideoLayout'
import FeaturedLayout from './layouts/FeaturedLayout'
import PlaylistLayout from './layouts/PlaylistLayout'
import UploadLayout from './layouts/UploadLayout/UploadLayout'

const App: React.FC = () => (
    <MediaProvider>
        <VideoLayout />
        <FeaturedLayout />
        <PlaylistLayout />
        <UploadLayout />
    </MediaProvider>
)

export default App
