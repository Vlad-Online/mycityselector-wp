import {BooleanField, Button} from "ra-ui-materialui";
import * as React from "react";
import {useNotify, useRefresh, useUnselectAll, useUpdateMany} from "ra-core";

export const BooleanWrapper = (props) => {
    const newProps = {
        ...props,
        record: {
            ...props.record,
            [props.source]: !!parseInt(props?.record?.[props.source])
        }
    }
    return <BooleanField {...newProps}/>
}

export const PublishButton = (props) => {
    const refresh = useRefresh();
    const notify = useNotify();
    const unselectAll = useUnselectAll();
    const [updateMany, {loading}] = useUpdateMany(
        props.resource,
        props.selectedIds,
        {status: 1},
        {
            onSuccess: () => {
                refresh();
                notify('Успешно');
                unselectAll(props.resource);
            },
            onFailure: error => notify('Произошла ошибка ' + error.toString(), 'warning'),
        }
    );
    return (
        <Button
            label={props.label ?? "Опубликовать"}
            disabled={loading}
            onClick={updateMany}
        />
    );
}

export const UnPublishButton = (props) => {
    const refresh = useRefresh();
    const notify = useNotify();
    const unselectAll = useUnselectAll();
    const [updateMany, {loading}] = useUpdateMany(
        props.resource,
        props.selectedIds,
        {status: 0},
        {
            onSuccess: () => {
                refresh();
                notify('Успешно');
                unselectAll(props.resource);
            },
            onFailure: error => notify('Произошла ошибка ' + error.toString(), 'warning'),
        }
    );
    return (
        <Button
            label={props.label ?? "Снять с публикации"}
            disabled={loading}
            onClick={updateMany}
        />
    );
}
