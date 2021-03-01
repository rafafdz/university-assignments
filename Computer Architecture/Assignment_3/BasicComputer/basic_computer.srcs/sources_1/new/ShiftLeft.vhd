library IEEE;
use IEEE.STD_LOGIC_1164.ALL;

entity ShiftLeft is
    Port ( in0  : in std_logic_vector (11 downto 0);
           out_shift  : out std_logic_vector (11 downto 0));
end ShiftLeft;

architecture Behavioral of ShiftLeft is

begin
  out_shift(0) <= '0';
  out_shift(1) <= in0(0);
  out_shift(2) <= in0(1);
  out_shift(3) <= in0(2);
  out_shift(4) <= in0(3);
  out_shift(5) <= in0(4);
  out_shift(6) <= in0(5);
  out_shift(7) <= in0(6);
  out_shift(8) <= in0(7);
  out_shift(9) <= in0(8);
  out_shift(10) <= in0(9);
  out_shift(11) <= in0(10);

end Behavioral;
