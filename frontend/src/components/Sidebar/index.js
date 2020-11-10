import React, { memo } from 'react';
import './style.css';
import {SidebarMenu} from "./Menu";


const Sidebar =  memo(() => (
    <div className="sidebar">
        <div className="sidebar-wrapper">
            <SidebarMenu />
        </div>
    </div>
));

export default Sidebar;
