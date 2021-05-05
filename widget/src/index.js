import React from "react";
import ReactDOM from "react-dom";
import { McsWidget } from "./McsWidget";

//console.log(window.mcs_options?.title);

ReactDOM.render(
	<React.StrictMode>
		<McsWidget
			options={{
				title: window.mcs?.options?.title ?? "Select your location",
				mode: window.mcs?.options?.mode ?? 0,
			}}
			data={window.mcs?.data ?? {}}
		/>
	</React.StrictMode>,
	document.getElementById("mcs-widget")
);
