# Run latest Tailwindcss CLI

1) Download latest version of tailwind from `https://github.com/tailwindlabs/tailwindcss/releases/`.

2) Place file in e.g. `C:\Tools\tailwindcss` or `/mnt/c/Tools/tailwindcss`.

3) Open a projekt in terminal:
```shell
cd C:\laragon\www\trp-solutions`
```
or
```sh
cd /mnt/c/laragon/www/trp-solutions
```

4) Run tailwind in the project with:
```shell
& 'C:\Tools\tailwindcss\tailwindcss-windows-x64.exe' `
	-i './assets/tw/general.css' `
	-o './assets/css/styles.css' `
	--watch
```
or
```sh
/mnt/c/Tools/tailwindcss/tailwindcss-windows-x64.exe \
	-i './assets/tw/general.css' \
	-o './assets/css/styles.css' \
	--watch
```

5) Create an alias if necessary.
