library IEEE;
use IEEE.STD_LOGIC_1164.ALL;


entity HA is
    Port ( a : in STD_LOGIC;
           b : in STD_LOGIC;
           s : out STD_LOGIC;
           c : out STD_LOGIC);
end HA;

architecture Behavioral of HA is

begin

s <= a xor b;
c <= a and b;

end Behavioral;
