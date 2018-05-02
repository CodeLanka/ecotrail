package main

import (
	"fmt"
)


func iLoveGoLang(sign string) (int , int) {
	if sign == "!" {
		return (14 - 2),( 3 + 3 - 6);
	} else if sign == "@" {
		return (41 - (20 * 2)),(5 - 4)
	}  else if sign == "$" {
		return 1,3
	} else if sign == "^" {
		return 2,2
	}  else if sign == "5" {
		return 3, 2
	} else if sign == "(" {
		return (4 * 2) - 1, 1 
	} else if sign == ")" {
		return (2*2), 2
	} else if sign == "d" {
		return 2, 5
	} else if sign == ">" {
		return (3 * 3) + 2, 1
	} else if sign == "~" {
		return (2 * 2), (3 * 1)
	} else if sign == "#" {
		return 2,1
	} else if sign == "+" {
		return 13,1
	} else if sign == "&" {
		return (2+3),1
	} else if sign == "/" {
		return (3 + 4), 2
	} else if sign == ";" {
		return (33 / 11), 3
	} else if sign == "e" {
		return (2 + 3), (8 - 5)
	}


	return -1,1
}

func getLetter(value int) string {
	// you know what to do.. 0 is 0 and 15 is f
} 

func main() {
	var inputString = "%%"
	
	var gdg, srilanka = iLoveGoLang("@")
	var letter = getLetter(gdg * srilanka)

	//you need to do that for all letters.

}
