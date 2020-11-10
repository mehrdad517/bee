import React, { memo } from 'react';
import Pagination from 'react-js-pagination';
import './style.css';

const Paginator = (props) => {

    const { activePage,itemsCountPerPage, totalItemsCount, onChange} = props;

    return(
        <Pagination
            activePage={activePage}
            itemsCountPerPage={itemsCountPerPage}
            totalItemsCount={totalItemsCount}
            pageRangeDisplayed={5}
            onChange={onChange}
        />

    );
};

export default memo(Paginator);