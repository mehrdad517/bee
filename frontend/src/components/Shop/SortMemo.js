import Chip from "@material-ui/core/Chip";
import React, { memo } from "react";


const ShopSortMemo = (props) => {

    const { list, onClick, selected } = props;

    return(
        <div className={'shop-sort-chip'}>
            {list && list.map((s, i) => {
                return(
                    <div className={'shop-sort-chip-element'}>
                        <Chip onClick={() => onClick('sort', i)} clickable={true} color={selected === i ? 'secondary' : 'primary'} label={s.title} />
                    </div>
                );
            })}
        </div>
    );
}

export default memo(ShopSortMemo);
