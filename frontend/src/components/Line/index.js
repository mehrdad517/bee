import React, { memo } from 'react';
import { Link } from 'react-router-dom';
import './style.css';

const Line = memo(({ title, link }) => (
    <div className="line">
        <h3>{title}</h3>
        {link !== '' && <Link to={link}>مشاهده همه</Link>}
    </div>
));

export default Line;