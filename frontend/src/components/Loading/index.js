import React from 'react';
import './style.css'

const Loading = (props) => {
    return (
        <>
            <div className="loading"/>
            <style jsx>
                {`
            .loading {
              top: calc(50% - ${(props.width)/2}px);
              right: calc(50% - ${(props.height)/2}px);
              width : ${props.width}px;
              height : ${props.height}px
              }
            `}
            </style>
        </>
    );
};

export default Loading;