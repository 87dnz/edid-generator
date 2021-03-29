/*
   400x1280.S: EDID data set for standard 400x1280 50 Hz monitor
*/

/* EDID */
#define VERSION 1
#define REVISION 3

/* Display */
#define CLOCK 43000 /* kHz */

#define XPIX 400
#define YPIX 1280

#define XY_RATIO XY_RATIO_16_9

#define XBLANK 250
#define YBLANK 42

#define XOFFSET 100
#define XPULSE 10

#define YOFFSET (63+20)
#define YPULSE (63+20)

#define DPI 72

#define VFREQ 50 /* Hz */

#define TIMING_NAME "400x1280"

/* No ESTABLISHED_TIMINGx_BITS */
#define HSYNC_POL 0
#define VSYNC_POL 1

#include "edid.S"
