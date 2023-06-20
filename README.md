```
git clone git@github.com:87dnz/edid-generator.git
cd edid-generator
```

get desired __VIC number__ from [EIA/CEA-861 standard resolutions and timings ](https://en.wikipedia.org/wiki/Extended_Display_Identification_Data#CEA_EDID_Timing_Extension_data_format_-_Version_3) table (e.g. VIC 63 for 1920 x 1080 @ 120 Hz)

```
edid-decode -X --vic 63 > 1080p120.Modeline
```

in `1080p120.Modeline` : change `"1920x1080_120.00"` to a shorter name (12 characters max) like `"1080p120"`

```
./modeline2edid 1080p120.Modeline
echo '#define CRC 0x00' >> 1080p120.S
echo '#define TIMING_NAME "1080p120"' >> 1080p120.S
ls
```

you should see files `1080p120.Modeline , 1080p120.S , Makefile , edid.S , hex` in current folder

in `1080p120.S` : ensure that :

- `TIMING_NAME` value is 12 characters max

- `VFREQ` value is `120` (for 120 Hz)

- `HSYNC_POL` value is `1` (for +Hsync) or `0` (for -Hsync)

- `VSYNC_POL` value is `1` (for +Vsync) or `0` (for -Vsync)

- `DPI` value matches your monitor display size ([DPI Calculator](https://www.sven.de/dpi/))

```
make
edid-decode 1080p120.bin
```

you should see `Checksum: 0x00 (should be <GoodHexValue>)`

in `1080p120.S` : replace value for `CRC` with that good hex value

```
make clean
make
```

the file `1080p120.bin` is your new EDID

`edid-decode 1080p120.bin`

you should not see any error

---

forked from [FREEWING-JP/edid-generator](https://github.com/FREEWING-JP/edid-generator)

orginial README below

---

2023/04/14 FREE WING mod.
==============

http://www.neko.ne.jp/~freewing/hardware/hdmi_edid_dummy_adapter_custom/

Original
* https://github.com/akatrevorjay/edid-generator

Merged
* https://github.com/zigmars/edid-generator
* https://github.com/JeonghwaCho/edid-generator

edid-generator
==============

Hackerswork to generate an EDID binary file from given Xorg Modelines

An extension of the awesome work provided in the Linux kernel documentation (in `docs/EDID`).

Simplifies the process greatly by allowing you to use a standard modeline as well as automatically calculating the CRC
and applying it to the resulting image.

Requirements
------------

```
sudo apt install zsh edid-decode automake dos2unix
```

Usage
-----

If you don't have a `<mode>.S` prepared yet, generate one using a file containing Xorg Modelines. Lines that do not
contain modelines are ignored, so you can just read right from `xorg.conf`.

```s
./modeline2edid /etc/X11/xorg.conf
```

You can also just read from `stdin` the way you'd expect:

```s
./modeline2edid
# or explicitly:
./modeline2edid -
```

After this creates your `<name>.S` files for each modeline it encounters, simply `make`:

```sh
make
```

The end result, providing all goes well, is a glorious EDID bin image for each mode you gave to it. A `<name>.S` file
is templated, and then `make` is invoked to compile it into `<name>.bin`. It's actually compiled twice; once with an
invalid CRC in order to generate said CRC to enter it into the template, after which we recompile, hence glorious bins.

NOTE: If you use a ratio other than 16:9, you'll need to specify it at the end of the modeline.as `ratio=4:3`.
Ratios are hard defined in `edid.S`, so if you are trying to do something non-standard you'll need to add it.

Why?
----

Many monitors and TVs (both high and low end) provide invalid EDID data. After dealing with this for years, I wanted to
automate this process.

The final straw was when I bought a cheap Sceptre 4K tv at a rather affordable ~$225 and ran into a long series of hurdles to get it to operate
as expected at `3840x2160@60`. After doing this enough times, I had to automate it or I was going to go crazy.

I used this to quickly iterate while troubleshhooting, finally it's all working from KMS all the way down to X!

(Via `drm_kms_helper.edid_firmware=DP-1:edid/blah.bin` if you're interested. I'm using radeon + intel, with nvidia you
have to specify it in `xorg.conf`/`xorg.conf.d` as they don't yet support KMS for the fb console yet; their beta
drivers, 367 at the time of writing, only support using KMS for the xorg server.)

Sometimes I hate being such a perfectionist. Keep in mind this project was made in a couple hours, I certainly didn't
attempt to polish it in the least ;)
