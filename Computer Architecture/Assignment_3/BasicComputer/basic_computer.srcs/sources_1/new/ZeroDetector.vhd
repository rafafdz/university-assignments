library IEEE;
use IEEE.STD_LOGIC_1164.ALL;

entity ZeroDetector is
    Port ( in0  : in std_logic_vector (11 downto 0);
           out_zero  : out std_logic);
end ZeroDetector;

architecture Behavioral of ZeroDetector is

signal or0 : STD_LOGIC;
signal or1 : STD_LOGIC;
signal or2 : STD_LOGIC;
signal or3 : STD_LOGIC;
signal or4 : STD_LOGIC;
signal or5 : STD_LOGIC;
signal or6 : STD_LOGIC;
signal or7 : STD_LOGIC;
signal or8 : STD_LOGIC;
signal or9 : STD_LOGIC;
signal zero_inv : STD_LOGIC;

begin

or0 <= in0(0) or in0(1);
or1 <= in0(2) or or0;
or2 <= in0(3) or or1;
or3 <= in0(4) or or2;
or4 <= in0(5) or or3;
or5 <= in0(6) or or4;
or6 <= in0(7) or or5;
or7 <= in0(8) or or6;
or8 <= in0(9) or or7;
or9 <= in0(10) or or8;
zero_inv <= in0(11) or or9;
out_zero <= not zero_inv;


end Behavioral;
