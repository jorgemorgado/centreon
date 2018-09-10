import React from 'react'
import ReactDOM from 'react-dom'
import TopHeader from './frontSrc/Header/TopHeaderContainer'

log.console('INDEX...')
ReactDOM.hydrate(
  <TopHeader />,
  document.getElementById('header-react')
);
