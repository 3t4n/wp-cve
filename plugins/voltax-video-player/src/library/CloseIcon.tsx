import React from 'react'

export const CloseIcon = ({ fill }: { fill: string }): JSX.Element => (
    <>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <g strokeLinecap="round" strokeLinejoin="round">
                <g stroke={fill ?? '#D4CDE4'} strokeWidth="3">
                    <g>
                        <g>
                            <g>
                                <path
                                    d="M10.909 0L0 10.909M0 0L10.909 10.909"
                                    transform="translate(-1270 -173) translate(380 147) translate(890 26) translate(4.545 4.545)"
                                />
                            </g>
                        </g>
                    </g>
                </g>
            </g>
        </svg>
    </>
)
